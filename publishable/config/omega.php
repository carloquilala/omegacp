<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User config
    |--------------------------------------------------------------------------
    |
    | Here you can specify omega user configs
    |
    */

    'user' => [
        'add_default_role_on_register' => true,
        'default_role'                 => 'user',
        'namespace'                    => App\User::class,
        'default_avatar'               => 'users/default.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify omega controller settings
    |
    */

    'controllers' => [
        'namespace' => 'artworx\\omegacp\\Http\\Controllers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | Here you can specify default model namespace when creating BREAD.
    | Must include trailing backslashes. If not defined the default application
    | namespace will be used.
    |
    */

    'models' => [
        //'namespace' => 'App\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Path to the OmegaCP Assets
    |--------------------------------------------------------------------------
    |
    | Here you can specify the location of the omega assets path
    |
    */

    'assets_path' => '/vendor/artworx/omegacp/assets',

    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */

    'storage' => [
        'disk' => 'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify omega database settings
    |
    */

    'database' => [
        'tables' => [
            'hidden' => ['migrations', 'data_rows', 'data_types', 'menu_items', 'password_resets', 'permission_role', 'permissions', 'settings'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard config
    |--------------------------------------------------------------------------
    |
    | Here you can modify some aspects of your dashboard
    |
    */

    'dashboard' => [
        // Add custom list items to navbar's dropdown
        'navbar_items' => [
            'Profile' => [
                'route'         => 'omega.profile',
                'classes'       => 'class-full-of-rum',
                'icon_class'    => 'omega-person',
            ],
            'Home' => [
                'route'         => '/',
                'icon_class'    => 'omega-home',
                'target_blank'  => true,
            ],
            'Logout' => [
                'route'      => 'omega.logout',
                'icon_class' => 'omega-power',
            ],
        ],
        'data_tables' => [
            'responsive' => true, // Use responsive extension for jQuery dataTables that are not server-side paginated
        ],
        'widgets' => [
            'artworx\\omegacp\\Widgets\\UserDimmer',
            'artworx\\omegacp\\Widgets\\PostDimmer',
            'artworx\\omegacp\\Widgets\\PageDimmer',
        ],
    ],

    'login' => [
        'gradient_a' => '#ffffff',
        'gradient_b' => '#ffffff',
    ],

    'primary_color' => '#22A7F0',
];
