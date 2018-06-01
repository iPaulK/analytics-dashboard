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
            $router->get('ga/account/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\ProfilesController@index']);
            $router->get('ga/webproperty-adwords-links/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\WebPropertyAdWordsLinksController@index']);
            $router->get('ga/custom-data-sources/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\CustomDataSourcesController@index']);

            $router->get('ga/custom-dimensions/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\CustomDimensionsController@index']);

            $router->get('ga/custom-metrics/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\CustomMetricsController@index']);

            $router->get('ga/goals/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', ['uses' => 'GoogleAnalytics\GoalsController@index']);
            $router->get('ga/profile-filter-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', ['uses' => 'GoogleAnalytics\ProfileFilterLinksController@index']);
            $router->get('ga/profile-user-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', ['uses' => 'GoogleAnalytics\ProfileUserLinksController@index']);
            $router->get('ga/webproperty-user-links/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\WebPropertyUserLinksController@index']);
            
            $router->get('ga/filters/{accountId}', ['uses' => 'GoogleAnalytics\FiltersController@index']);
            $router->get('ga/account-user-links/{accountId}', ['uses' => 'GoogleAnalytics\AccountUserLinksController@index']);

            /*
             |--------------------------------------------------------------------------
             | GoogleTagManager
             |--------------------------------------------------------------------------
             */
            $router->get('gtm/accounts', ['uses' => 'GoogleTagManager\AccountsController@index']);

            /*
             |--------------------------------------------------------------------------
             | GoogleSeacrhConsole
             |--------------------------------------------------------------------------
             */
            $router->get('gsc/sites', ['uses' => 'GoogleSearchConsole\SitesController@index']);
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
    $router->options('ga/account/{accountId}/webproperty/{webPropertyId}', 'GoogleAnalytics\ProfilesController@options');
    $router->options('ga/webproperty-adwords-links/{accountId}/webproperty/{webPropertyId}', 'GoogleAnalytics\WebPropertyAdWordsLinksController@options');
    $router->options('ga/custom-data-sources/{accountId}/webproperty/{webPropertyId}', 'GoogleAnalytics\CustomDataSourcesController@options');
    $router->options('ga/custom-dimensions/{accountId}/webproperty/{webPropertyId}', 'GoogleAnalytics\CustomDimensionsController@options');
    $router->options('ga/custom-metrics/{accountId}/webproperty/{webPropertyId}', 'GoogleAnalytics\CustomMetricsController@options');
    $router->options('ga/goals/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', 'GoogleAnalytics\GoalsController@options');
    $router->options('ga/profile-filter-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', 'GoogleAnalytics\ProfileFilterLinksController@options');
    $router->options('ga/profile-user-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', 'GoogleAnalytics\ProfileUserLinksController@options');
    $router->options('ga/filters/{accountId}', 'GoogleAnalytics\FiltersController@options');
    $router->options('ga/account-user-links/{accountId}', 'GoogleAnalytics\AccountUserLinksController@options');
    $router->options('ga/webproperty-user-links/{accountId}/webproperty/{webPropertyId}', ['uses' => 'GoogleAnalytics\WebPropertyUserLinksController@index']);


    $router->options('gtm/accounts', 'GoogleTagManager\AccountsController@options');


    $router->options('gsc/sites', 'GoogleSearchConsole\SitesController@options');
});
