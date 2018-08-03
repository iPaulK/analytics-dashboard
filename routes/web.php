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
        });

        /*
         |--------------------------------------------------------------------------
         | GoogleAnalytics
         |--------------------------------------------------------------------------
         */
        $router->group(['prefix' => 'ga'], function ($router) {
            // Accounts
            $router->get('accounts', [
                'uses' => 'Google\Analytics\AccountsController@index'
            ]); // GET /v1/ga/accounts

            $router->group(['middleware' => ['ga_employee']], function ($router) {

                $router->get('accounts/{accountId}/history', [
                    'uses' => 'Google\Analytics\AccountsController@history'
                ]); // GET /v1/ga/accounts/{accountId}/history


                // Webproperties
                $router->get('accounts/{accountId}/webproperties', [
                    'uses' => 'Google\Analytics\WebPropertiesController@index'
                ]); // GET /v1/ga/accounts/{accountId}/history
                
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/history', [
                    'uses' => 'Google\Analytics\WebPropertiesController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/history


                // Account user links
                $router->get('accounts/{accountId}/account-user-links', [
                    'uses' => 'Google\Analytics\AccountUserLinksController@index'
                ]); // GET /v1/ga/accounts/{accountId}/account-user-links

                $router->get('accounts/{accountId}/account-user-links/{userLinkId}/history', [
                    'uses' => 'Google\Analytics\AccountUserLinksController@history'
                ]); // GET /v1/ga/accounts/{accountId}/account-user-links/{userLinkId}/history


                // Filters
                $router->get('accounts/{accountId}/filters', [
                    'uses' => 'Google\Analytics\FiltersController@index'
                ]); // GET /v1/ga/accounts/{accountId}/account-user-links

                $router->get('accounts/{accountId}/filters', [
                    'uses' => 'Google\Analytics\FiltersController@history'
                ]); // GET /v1/ga/accounts/{accountId}/filters


                // Webproperty AdWords Links
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/adwords-links', [
                    'uses' => 'Google\Analytics\WebPropertyAdWordsLinksController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/adwords-links

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/adwords-links/{entityAdWordsLinkId}/history', [
                    'uses' => 'Google\Analytics\WebPropertyAdWordsLinksController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/adwords-links/{entityAdWordsLinkId}/history
            

                // Custom Data Sources
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/custom-data-sources', [
                    'uses' => 'Google\Analytics\CustomDataSourcesController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-data-sources

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/custom-data-sources/{customDataSourceId}/history', [
                    'uses' => 'Google\Analytics\CustomDataSourcesController@history'
                ]); //GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-data-sources/{customDataSourceId}/history
            

                // Custom Dimensions
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/custom-dimensions', [
                    'uses' => 'Google\Analytics\CustomDimensionsController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-dimensions

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/custom-dimensions/{customDimensionId}/history', [
                    'uses' => 'Google\Analytics\CustomDimensionsController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-dimensions/{customDimensionId}/history


                // Custom Metrics
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/custom-metrics', [
                    'uses' => 'Google\Analytics\CustomMetricsController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-metrics

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/custom-metrics/{customMetricId}/history', [
                    'uses' => 'Google\Analytics\CustomMetricsController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-metrics/{customMetricId}/history

                
                // Webproperty User Links
                /*$router->get('accounts/{accountId}/webproperties/{webPropertyId}/webproperty-user-links', [
                    'uses' => 'Google\Analytics\WebPropertyUserLinksController@index'
                ]);

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/webproperty-user-links/{customMetricId}/history', [
                    'uses' => 'Google\Analytics\WebPropertyUserLinksController@index'
                ]);*/


                // Profiles
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/profiles', [
                    'uses' => 'Google\Analytics\ProfilesController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles
                
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/history', [
                    'uses' => 'Google\Analytics\ProfilesController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/history


                // Goals
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/goals', [
                    'uses' => 'Google\Analytics\GoalsController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/goals

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/goals/{goalId}/history', [
                    'uses' => 'Google\Analytics\GoalsController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/goals/{goalId}/history


                // Profile Filter Links
                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/profile-filter-links', [
                    'uses' => 'Google\Analytics\ProfileFilterLinksController@index'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/profile-filter-links

                $router->get('accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/profile-filter-links/{profileLinkId}/history', [
                    'uses' => 'Google\Analytics\ProfileFilterLinksController@history'
                ]); // GET /v1/ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/profile-filter-links/{profileLinkId}/history
                

                // Profile User Links
                // $router->get('ga/profile-user-links/{accountId}/webproperty/{webPropertyId}/profile/{profileId}', [
                //     'uses' => 'Google\Analytics\ProfileUserLinksController@index'
                // ]);
            });
        });

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

    // Auth
    $router->options('access-tokens', 'AuthController@options');
    $router->options('logout', 'AuthController@options');
    $router->options('refresh', 'AuthController@options');
    $router->options('me', 'AuthController@options');
    $router->options('users', 'UsersController@options');
    $router->options('users/{id}', 'UsersController@options');
    
    // Accounts
    $router->options('ga/accounts', 'Google\Analytics\AccountsController@options');
    $router->options('ga/accounts/{accountId}/history', 'Google\Analytics\AccountsController@options');

    // Filters
    $router->options('ga/accounts/{accountId}/filters', 'Google\Analytics\FiltersController@options');
    $router->options('ga/accounts/{accountId}/filters/{filterId}/history', 'Google\Analytics\FiltersController@options');
    
    // Webproperties
    $router->options('ga/accounts/{accountId}/webproperties', 'Google\Analytics\WebPropertiesController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/history', 'Google\Analytics\WebPropertiesController@options');
    
    // Profiles
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles', 'Google\Analytics\ProfilesController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/history', 'Google\Analytics\ProfilesController@options');

    // Account User Links
    $router->options('ga/accounts/{accountId}/account-user-links', 'Google\Analytics\AccountUserLinksController@options');
    $router->options('ga/accounts/{accountId}/account-user-links/{userLinkId}/history', 'Google\Analytics\AccountUserLinksController@options');
    
    // Webproperty AdWords Links
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/adwords-links', 'Google\Analytics\WebPropertyAdWordsLinksController@options');
    $router->options('ga/accounts/webproperties/{webPropertyId}/adwords-links/{entityAdWordsLinkId}/history', 'Google\Analytics\WebPropertyAdWordsLinksController@options');

    // Custom Data Sources
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-data-sources', 'Google\Analytics\CustomDataSourcesController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-data-sources/{customDataSourceId}/history', 'Google\Analytics\CustomDataSourcesController@options');

    // Custom Dimensions
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-dimensions', 'Google\Analytics\CustomDataSourcesController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-dimensions/{customDimensionId}/history', 'Google\Analytics\CustomDataSourcesController@options');

    // Custom Metrics
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-metrics', 'Google\Analytics\CustomMetricsController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/custom-metrics/{customMetricId}/history', 'Google\Analytics\CustomMetricsController@options');
    
    // Goals
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/goals', 'Google\Analytics\GoalsController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/goals/{goalId}/history', 'Google\Analytics\GoalsController@options');
    

    // Profile Filter Links
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/profile-filter-links', 'Google\Analytics\ProfileFilterLinksController@options');
    $router->options('ga/accounts/{accountId}/webproperties/{webPropertyId}/profiles/{profileId}/profile-filter-links/{profileFilterId}/history', 'Google\Analytics\ProfileFilterLinksController@options');


    //$router->options('ga/profile-user-links/{accountId}/webproperties/{webPropertyId}/profile/{profileId}', 'Google\Analytics\ProfileUserLinksController@options');
    //$router->options('ga/webproperty-user-links/{accountId}/webproperties/{webPropertyId}', ['uses' => 'Google\Analytics\WebPropertyUserLinksController@index']);


    $router->options('gtm/accounts', 'Google\TagManager\AccountsController@options');
    $router->options('gtm/accounts/{accountId}/history', 'Google\TagManager\AccountsController@options');


    $router->options('gsc/sites', 'Google\SearchConsole\SitesController@options');
    $router->options('gsc/sites/{siteUrl}/history', 'Google\SearchConsole\SitesController@options');
});
