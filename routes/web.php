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
             | Google\Analytics
             |--------------------------------------------------------------------------
             */

            // accounts
            $router->get('ga/accounts', ['uses' => 'Google\Analytics\AccountsController@index']);
            $router->get('ga/accounts/{accountId}/history', ['uses' => 'Google\Analytics\AccountsController@history']);

            // webproperties
            $router->get('ga/accounts/{accountId}/webproperties', ['uses' => 'Google\Analytics\WebPropertiesController@index']);
            $router->get('ga/accounts/{accountId}/webproperties/{webPropertyId}/history', ['uses' => 'Google\Analytics\WebPropertiesController@history']);

            // profiles
            $router->get('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles', ['uses' => 'Google\Analytics\ProfilesController@index']);
            $router->get('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/history', ['uses' => 'Google\Analytics\ProfilesController@history']);

            // account user links
            $router->get('ga/accounts/{accountId}/account-user-links', ['uses' => 'Google\Analytics\AccountUserLinksController@index']);
            $router->get('ga/accounts/{accountId}/account-user-links/{userLinkId}/history', ['uses' => 'Google\Analytics\AccountUserLinksController@history']);

            // filters
            $router->get('ga/accounts/{accountId}/filters', ['uses' => 'Google\Analytics\FiltersController@index']);
            $router->get('ga/accounts/{accountId}/filters/{filterId}/history', ['uses' => 'Google\Analytics\FiltersController@history']);


            $router->get('ga/webproperty-adwords-links/{accountId}/webproperty/{webPropertyId}', ['uses' => 'Google\Analytics\WebPropertyAdWordsLinksController@index']);
            $router->get('ga/custom-data-sources/{accountId}/webproperty/{webPropertyId}', ['uses' => 'Google\Analytics\CustomDataSourcesController@index']);

            $router->get('ga/custom-dimensions/{accountId}/webproperty/{webPropertyId}', ['uses' => 'Google\Analytics\CustomDimensionsController@index']);

            $router->get('ga/custom-metrics/{accountId}/webproperty/{webPropertyId}', ['uses' => 'Google\Analytics\CustomMetricsController@index']);

            $router->get('ga/goals/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', ['uses' => 'Google\Analytics\GoalsController@index']);
            $router->get('ga/profile-filter-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', ['uses' => 'Google\Analytics\ProfileFilterLinksController@index']);
            $router->get('ga/profile-user-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', ['uses' => 'Google\Analytics\ProfileUserLinksController@index']);
            $router->get('ga/webproperty-user-links/{accountId}/webproperty/{webPropertyId}', ['uses' => 'Google\Analytics\WebPropertyUserLinksController@index']);

            /*
             |--------------------------------------------------------------------------
             | GoogleTagManager
             |--------------------------------------------------------------------------
             */
            $router->get('gtm/accounts', ['uses' => 'Google\TagManager\AccountsController@index']);
            $router->get('gtm/accounts/{accountId}/history', ['uses' => 'Google\TagManager\AccountsController@history']);

            /*
             |--------------------------------------------------------------------------
             | GoogleSeacrhConsole
             |--------------------------------------------------------------------------
             */
            $router->get('gsc/sites', ['uses' => 'Google\SearchConsole\SitesController@index']);
            $router->get('gsc/sites/{siteUrl}/history', ['uses' => 'Google\SearchConsole\SitesController@history']);
        });
    });

    $router->options('access-tokens', 'AuthController@options');
    $router->options('logout', 'AuthController@options');
    $router->options('refresh', 'AuthController@options');
    $router->options('me', 'AuthController@options');
    $router->options('users', 'UsersController@options');
    $router->options('users/{id}', 'UsersController@options');
    
    $router->options('ga/accounts', 'Google\Analytics\AccountsController@options');
    $router->options('ga/accounts/{accountId}/history', 'Google\Analytics\AccountsController@options');

    // filters
    $router->options('ga/accounts/{accountId}/filters', 'Google\Analytics\FiltersController@options');
    $router->options('ga/accounts/{accountId}/filters/{filterId}/history', 'Google\Analytics\FiltersController@options');
    
    $router->options('ga/accounts/{accountId}/webproperties', 'Google\Analytics\WebPropertiesController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/history', 'Google\Analytics\WebPropertiesController@options');
    
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles', 'Google\Analytics\ProfilesController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/history', 'Google\Analytics\ProfilesController@options');

    $router->options('ga/accounts/{accountId}/account-user-links', 'Google\Analytics\AccountUserLinksController@options');
    $router->options('ga/accounts/{accountId}/account-user-links/{userLinkId}/history', 'Google\Analytics\AccountUserLinksController@options');
    
    $router->options('ga/webproperty-adwords-links/{accountId}/webproperty/{webPropertyId}', 'Google\Analytics\WebPropertyAdWordsLinksController@options');
    $router->options('ga/custom-data-sources/{accountId}/webproperty/{webPropertyId}', 'Google\Analytics\CustomDataSourcesController@options');
    $router->options('ga/custom-dimensions/{accountId}/webproperty/{webPropertyId}', 'Google\Analytics\CustomDimensionsController@options');
    $router->options('ga/custom-metrics/{accountId}/webproperty/{webPropertyId}', 'Google\Analytics\CustomMetricsController@options');
    $router->options('ga/goals/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', 'Google\Analytics\GoalsController@options');
    $router->options('ga/profile-filter-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', 'Google\Analytics\ProfileFilterLinksController@options');
    $router->options('ga/profile-user-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', 'Google\Analytics\ProfileUserLinksController@options');
    $router->options('ga/webproperty-user-links/{accountId}/webproperty/{webPropertyId}', ['uses' => 'Google\Analytics\WebPropertyUserLinksController@index']);


    $router->options('gtm/accounts', 'Google\TagManager\AccountsController@options');
    $router->options('gtm/accounts/{accountId}/history', 'Google\TagManager\AccountsController@options');


    $router->options('gsc/sites', 'Google\SearchConsole\SitesController@options');
    $router->options('gsc/sites/{siteUrl}/history', 'Google\SearchConsole\SitesController@options');
});
