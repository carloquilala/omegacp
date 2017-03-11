<?php

namespace artworx\omegacp;

use Arrilot\Widgets\Facade as Widget;
use Arrilot\Widgets\ServiceProvider as WidgetServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageServiceProvider;
use artworx\omegacp\Facades\Omega as OmegaFacade;
use artworx\omegacp\FormFields\After\DescriptionHandler;
use artworx\omegacp\Http\Middleware\OmegaAdminMiddleware;

class OmegaServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Omega', OmegaFacade::class);

        $this->app->singleton('omega', function () {
            return new Omega();
        });

        $this->loadHelpers();

        $this->registerAlertComponents();
        $this->registerFormFields();
        $this->registerWidgets();

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }

        if (!$this->app->runningInConsole() || config('app.env') == 'testing') {
            $this->registerAppCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router, Dispatcher $event)
    {
        if (config('omega.user.add_default_role_on_register')) {
            $app_user = config('omega.user.namespace');
            $app_user::created(function ($user) {
                if (is_null($user->role_id)) {
                    OmegaFacade::model('User')->findOrFail($user->id)
                        ->setRole(config('omega.user.default_role'))
                        ->save();
                }
            });
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'omega');

        if (app()->version() >= 5.4) {
            $router->aliasMiddleware('admin.user', OmegaAdminMiddleware::class);

            if (config('app.env') == 'testing') {
                $this->loadMigrationsFrom(realpath(__DIR__.'/migrations'));
            }
        } else {
            $router->middleware('admin.user', OmegaAdminMiddleware::class);
        }

        $this->registerViewComposers();

        $event->listen('omega.alerts.collecting', function () {
            $this->addStorageSymlinkAlert();
        });
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer('omega::*', function ($view) {
            $view->with('alerts', OmegaFacade::alerts());
        });
    }

    /**
     * Add storage symlink alert.
     */
    protected function addStorageSymlinkAlert()
    {
        $currentRouteAction = app('router')->current()->getAction();
        $routeName = is_array($currentRouteAction) ? array_get($currentRouteAction, 'as') : null;

        if ($routeName != 'omega.dashboard') {
            return;
        }

        if (request()->has('fix-missing-storage-symlink') && !file_exists(public_path('storage'))) {
            $this->fixMissingStorageSymlink();
        } elseif (!file_exists(public_path('storage'))) {
            $alert = (new Alert('missing-storage-symlink', 'warning'))
                ->title('Missing storage symlink')
                ->text('We could not find a storage symlink. This could cause problems with loading media files from the browser.')
                ->button('Fix it', '?fix-missing-storage-symlink=1');

            OmegaFacade::addAlert($alert);
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title('Missing storage symlink created')
                ->text('We just created the missing symlink for you.');
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title('Could not create missing storage symlink')
                ->text('We failed to generate the missing symlink for your application. It seems like your hosting provider does not support it.');
        }

        OmegaFacade::addAlert($alert);
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'artworx\\omegacp\\Alert\\Components\\'.ucfirst(camel_case($component)).'Component';

            $this->app->bind("omega.alert.components.{$component}", $class);
        }
    }

    /**
     * Register widget.
     */
    protected function registerWidgets()
    {
        $default_widgets = ['artworx\\omegacp\\Widgets\\UserDimmer', 'artworx\\omegacp\\Widgets\\PostDimmer', 'artworx\\omegacp\\Widgets\\PageDimmer'];
        $widgets = config('omega.dashboard.widgets', $default_widgets);

        foreach ($widgets as $widget) {
            Widget::group('omega::dimmers')->addWidget($widget);
        }
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $basePath = dirname(__DIR__);
        $publishable = [
            'omega_assets' => [
                "$basePath/publishable/assets" => public_path('vendor/artworx/omegacp/assets'),
            ],
            'migrations' => [
                "$basePath/publishable/database/migrations/" => database_path('migrations'),
            ],
            'seeds' => [
                "$basePath/publishable/database/seeds/" => database_path('seeds'),
            ],
            'demo_content' => [
                "$basePath/publishable/demo_content/" => storage_path('app/public'),
            ],
            'config' => [
                "$basePath/publishable/config/omega.php" => config_path('omega.php'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/publishable/config/omega.php', 'omega'
        );
    }

    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'date',
            'file',
            'image',
            'multiple_images',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'timestamp',
        ];

        foreach ($formFields as $formField) {
            $class = studly_case("{$formField}_handler");

            OmegaFacade::addFormField("artworx\\omegacp\\FormFields\\{$class}");
        }

        OmegaFacade::addAfterFormField(DescriptionHandler::class);

        event('omega.form-fields.registered');
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        $this->commands(Commands\AdminCommand::class);
    }

    /**
     * Register the commands accessible from the App.
     */
    private function registerAppCommands()
    {
        $this->commands(Commands\MakeModelCommand::class);
    }
}
