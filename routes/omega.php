<?php

use artworx\omegacp\Models\DataType;

/*
|--------------------------------------------------------------------------
| Omega Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Omega.
|
*/

Route::group(['as' => 'omega.'], function () {
    event('omega.routing', app('router'));

    $namespacePrefix = '\\'.config('omega.controllers.namespace').'\\';

    Route::get('login', ['uses' => $namespacePrefix.'OmegaAuthController@login', 'as' => 'login']);
    Route::post('login', ['uses' => $namespacePrefix.'OmegaAuthController@postLogin', 'as' => 'postlogin']);

    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event('omega.admin.routing', app('router'));

        // Main Admin and Logout Route
        Route::get('/', ['uses' => $namespacePrefix.'OmegaController@index', 'as' => 'dashboard']);
        Route::post('logout', ['uses' => $namespacePrefix.'OmegaController@logout', 'as' => 'logout']);
        Route::post('upload', ['uses' => $namespacePrefix.'OmegaController@upload', 'as' => 'upload']);

        Route::get('profile', ['uses' => $namespacePrefix.'OmegaController@profile', 'as' => 'profile']);

        try {
            foreach (DataType::all() as $dataTypes) {
                Route::resource($dataTypes->slug, $namespacePrefix.'OmegaBreadController');
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // Role Routes
        Route::resource('roles', $namespacePrefix.'OmegaRoleController');

        // Menu Routes
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix.'OmegaMenuController@builder', 'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix.'OmegaMenuController@order_item', 'as' => 'order']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix.'OmegaMenuController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix.'OmegaMenuController@add_item', 'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix.'OmegaMenuController@update_item', 'as' => 'update']);
            });
        });

        // Settings
        Route::group([
            'as'     => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'OmegaSettingsController@index', 'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'OmegaSettingsController@store', 'as' => 'store']);
            Route::put('/', ['uses' => $namespacePrefix.'OmegaSettingsController@update', 'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'OmegaSettingsController@delete', 'as' => 'delete']);
            Route::get('{id}/move_up', ['uses' => $namespacePrefix.'OmegaSettingsController@move_up', 'as' => 'move_up']);
            Route::get('{id}/move_down', ['uses' => $namespacePrefix.'OmegaSettingsController@move_down', 'as' => 'move_down']);
            Route::get('{id}/delete_value', ['uses' => $namespacePrefix.'OmegaSettingsController@delete_value', 'as' => 'delete_value']);
        });

        // Admin Media
        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'OmegaMediaController@index', 'as' => 'index']);
            Route::post('files', ['uses' => $namespacePrefix.'OmegaMediaController@files', 'as' => 'files']);
            Route::post('new_folder', ['uses' => $namespacePrefix.'OmegaMediaController@new_folder', 'as' => 'new_folder']);
            Route::post('delete_file_folder', ['uses' => $namespacePrefix.'OmegaMediaController@delete_file_folder', 'as' => 'delete_file_folder']);
            Route::post('directories', ['uses' => $namespacePrefix.'OmegaMediaController@get_all_dirs', 'as' => 'get_all_dirs']);
            Route::post('move_file', ['uses' => $namespacePrefix.'OmegaMediaController@move_file', 'as' => 'move_file']);
            Route::post('rename_file', ['uses' => $namespacePrefix.'OmegaMediaController@rename_file', 'as' => 'rename_file']);
            Route::post('upload', ['uses' => $namespacePrefix.'OmegaMediaController@upload', 'as' => 'upload']);
        });

        // Database Routes
        Route::group([
            'as'     => 'database.',
            'prefix' => 'database',
        ], function () use ($namespacePrefix) {
            Route::get('bread/{table}/create', ['uses' => $namespacePrefix.'OmegaDatabaseController@addBread', 'as' => 'create_bread']);
            Route::post('bread/', ['uses' => $namespacePrefix.'OmegaDatabaseController@storeBread', 'as' => 'store_bread']);
            Route::get('bread/{table}/edit', ['uses' => $namespacePrefix.'OmegaDatabaseController@addEditBread', 'as' => 'edit_bread']);
            Route::put('bread/{id}', ['uses' => $namespacePrefix.'OmegaDatabaseController@updateBread', 'as' => 'update_bread']);
            Route::delete('bread/{id}', ['uses' => $namespacePrefix.'OmegaDatabaseController@deleteBread', 'as' => 'delete_bread']);
        });

        Route::resource('database', $namespacePrefix.'OmegaDatabaseController');
    });
});
