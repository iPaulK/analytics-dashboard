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
 * Class WebPropertyAdWordsLinksController
 * @package App\Http\Controllers
 */
class WebPropertyAdWordsLinksController extends Controller
{
    /**
     * Lists webProperty-user links for a given web property.
     * (webpropertyUserLinks.listManagementWebpropertyUserLinks)
     *
     * @param string $accountId Account ID which the given web property belongs to.
     * @param string $webPropertyId Web Property ID for the webProperty-user links
     * to retrieve. Can either be a specific web property ID or '~all', which refers
     * to all the web properties that user has access to.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, $webPropertyId, Request $request)
    {
        try {
            // returns instance of \Google_Service_Storage
            $analytics = Google::make('analytics');
            // Get the list of webproperties for the authorized user.
            $webproperties = $analytics->management_webpropertyUserLinks->listManagementWebpropertyUserLinks($accountId, $webPropertyId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($webproperties->getItems());
    }

    protected function printResults($webproperties)
    {
        $data = [];
        foreach ($webproperties as $webproperty) {
            $data[] = [
                'id' => $webproperty->getId(),
                'kind' => $webproperty->getKind(),
                'selfLink' => $webproperty->getSelfLink(),
                'accountId' => $webproperty->getAccountId(),
                'internalWebPropertyId' => $webproperty->getInternalWebPropertyId(),
                'name' => $webproperty->getName(),
                'websiteUrl' => $webproperty->getWebsiteUrl(),
                'level' => $webproperty->getLevel(),
                'profileCount' => $webproperty->getProfileCount(),
                'industryVertical' => $webproperty->getIndustryVertical(),
                'defaultProfileId' => $webproperty->getDefaultProfileId(),
                'created' => $webproperty->getCreated(),
                'updated' => $webproperty->getUpdated(),
                'starred' => $webproperty->getStarred(),
                'isUpdatedLastDay' => $this->isUpdatedLastDay($webproperty->getUpdated()),
                'isCreatedLastDay' => $this->isCreatedLastDay($webproperty->getCreated()),
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