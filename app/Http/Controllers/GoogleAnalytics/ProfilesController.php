<?php

namespace App\Http\Controllers\GoogleAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use App\Facades\Google;

/**
 * Class ProfilesController
 * @package App\Http\Controllers
 */
class ProfilesController extends Controller
{
    /**
     * Get the list of webproperties
     *
     * @param string $accountId Account ID for the view (profiles) to retrieve. Can
     * either be a specific account ID or '~all', which refers to all the accounts
     * to which the user has access.
     * @param string $webPropertyId Web property ID for the views (profiles) to
     * retrieve. Can either be a specific web property ID or '~all', which refers to
     * all the web properties to which the user has access.
     * @param Request $request
     * @return json
     */
    public function index($accountId, $webPropertyId, Request $request)
    {
        // returns instance of \Google_Service_Storage
        $analytics = Google::make('analytics');
        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_profiles->listManagementProfiles($accountId, $webPropertyId);
        return $this->printResults($accounts->getItems());
    }

    protected function printResults($items)
    {
        $data = [];
        foreach ($items as $account) {
            $data[] = [
                'id' => $account->getId(),
                'kind' => $account->getKind(),
                'selfLink' => $account->getSelfLink(),
                'accountId' => $account->getAccountId(),
                'webPropertyId' => $account->getWebPropertyId(),
                'internalWebPropertyId' => $account->getInternalWebPropertyId(),
                'name' => $account->getName(),
                'currency' => $account->getCurrency(),
                'timezone' => $account->getTimezone(),
                'websiteUrl' => $account->getWebsiteUrl(),
                'defaultPage' => $account->getDefaultPage(),
                'excludeQueryParameters' => $account->getExcludeQueryParameters(),
                'siteSearchQueryParameters' => $account->getSiteSearchQueryParameters(),
                'stripSiteSearchQueryParameters' => $account->getStripSiteSearchQueryParameters(),
                'siteSearchCategoryParameters' => $account->getSiteSearchCategoryParameters(),
                'stripSiteSearchCategoryParameters' => $account->getStripSiteSearchCategoryParameters(),
                'type' => $account->getType(),
                'created' => $account->getCreated(),
                'updated' => $account->getUpdated(),
                'eCommerceTracking' => $account->getECommerceTracking(),
                'enhancedECommerceTracking' => $account->getEnhancedECommerceTracking(),
                'botFilteringEnabled' => $account->getBotFilteringEnabled(),
                'starred' => $account->getStarred(),
            ];
        }

        $result = [
          'jsonapi' => [
            'version' => "1.0"
          ],
          'data' => $data,
        ];
        return json_encode($result);
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}