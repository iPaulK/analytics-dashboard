<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Exceptions\GoogleServiceException;
use Google_Service_Exception;
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
      try {
          // returns instance of \Google_Service_Storage
          $analytics = Google::make('analytics');
          // Get the list of profiles for the authorized user.
          $profiles = $analytics->management_profiles->listManagementProfiles($accountId, $webPropertyId);
      } catch (Google_Service_Exception $e) {
          throw new GoogleServiceException($e->getMessage());
      }
      return $this->printResults($profiles->getItems());
    }

    protected function printResults($items)
    {
        $data = [];
        foreach ($items as $profile) {
            $data[] = [
                'id' => $profile->getId(),
                'kind' => $profile->getKind(),
                'selfLink' => $profile->getSelfLink(),
                'accountId' => $profile->getAccountId(),
                'webPropertyId' => $profile->getWebPropertyId(),
                'internalWebPropertyId' => $profile->getInternalWebPropertyId(),
                'name' => $profile->getName(),
                'currency' => $profile->getCurrency(),
                'timezone' => $profile->getTimezone(),
                'websiteUrl' => $profile->getWebsiteUrl(),
                'defaultPage' => $profile->getDefaultPage(),
                'excludeQueryParameters' => $profile->getExcludeQueryParameters(),
                'siteSearchQueryParameters' => $profile->getSiteSearchQueryParameters(),
                'stripSiteSearchQueryParameters' => $profile->getStripSiteSearchQueryParameters(),
                'siteSearchCategoryParameters' => $profile->getSiteSearchCategoryParameters(),
                'stripSiteSearchCategoryParameters' => $profile->getStripSiteSearchCategoryParameters(),
                'type' => $profile->getType(),
                'created' => $profile->getCreated(),
                'updated' => $profile->getUpdated(),
                'eCommerceTracking' => $profile->getECommerceTracking(),
                'enhancedECommerceTracking' => $profile->getEnhancedECommerceTracking(),
                'botFilteringEnabled' => $profile->getBotFilteringEnabled(),
                'starred' => $profile->getStarred(),
                'isUpdatedLastDay' => $this->isUpdatedLastDay($profile->getUpdated()),
                'isCreatedLastDay' => $this->isCreatedLastDay($profile->getCreated()),
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