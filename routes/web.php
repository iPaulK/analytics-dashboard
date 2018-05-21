<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return [];
});

$router->group(['prefix' => 'v1', 'middleware' => 'cors'], function ($router) {
    // Login
    $router->post('access-tokens', ['uses' => 'AuthController@login']);
    
    $router->get('helloanalytics', ['uses' => 'HelloAnalyticsController@index']);
    
    $router->group(['middleware' => ['auth:api']], function ($router) {
        // Logout
        $router->delete('logout', 'AuthController@logout');
        // Refresh token
        $router->post('refresh', 'AuthController@refresh');
        // User info
        $router->get('me', 'AuthController@getAuthenticatedUser');

        $router->group(['middleware' => ['role:admin']], function ($router) {
            /*
             |--------------------------------------------------------------------------
             | Users
             |--------------------------------------------------------------------------
             */
            $router->get('users', 'UsersController@index');
            $router->get('users/{id}', 'UsersController@show');
            $router->post('users', 'UsersController@create');
            $router->put('users/{id}', 'UsersController@update');
            $router->delete('users/{id}', 'UsersController@delete');

            /*
             |--------------------------------------------------------------------------
             | GoogleAnalytics
             |--------------------------------------------------------------------------
             */
            $router->get('ga/accounts', ['uses' => 'GoogleAnalytics\AccountsController@index']);
            $router->get('ga/webproperties/{accountId}', ['uses' => 'GoogleAnalytics\WebPropertiesController@index']);
        });
    });

    $router->options('access-tokens', 'AuthController@options');
    $router->options('logout', 'AuthController@options');
    $router->options('refresh', 'AuthController@options');
    $router->options('me', 'AuthController@options');
    $router->options('users', 'UsersController@options');
    $router->options('users/{id}', 'UsersController@options');
    
    $router->options('ga/accounts', 'GoogleAnalytics\AccountsController@options');
    $router->options('ga/webproperties/{accountId}', 'GoogleAnalytics\WebPropertiesController@options');
});
