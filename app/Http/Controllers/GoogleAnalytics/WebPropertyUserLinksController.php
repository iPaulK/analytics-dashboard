<?php

namespace App\Http\Controllers\GoogleAnalytics;

use App\Http\Controllers\Controller;
use App\Exceptions\GoogleServiceException;
use Google_Service_Exception;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use App\Facades\Google;

/**
 * Class WebPropertyUserLinksController
 * @package App\Http\Controllers
 */
class WebPropertyUserLinksController extends Controller
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
            $webpropertyUserLinks = $analytics->management_webpropertyUserLinks->listManagementWebpropertyUserLinks($accountId, $webPropertyId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($webpropertyUserLinks->getItems());
    }

    protected function printResults($webpropertyUserLinks)
    {
        $data = [];
        foreach ($webpropertyUserLinks as $webpropertyUserLink) {
            $data[] = [
                'id' => $webpropertyUserLink->getId(),
                'kind' => $webpropertyUserLink->getKind(),
                'selfLink' => $webpropertyUserLink->getSelfLink(),
                'entity' => $webpropertyUserLink->getEntity(),
                'userRef' => $webpropertyUserLink->getUserRef(),
                'permissions' => $webpropertyUserLink->getPermissions(),
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