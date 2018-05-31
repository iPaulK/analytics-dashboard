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
 * Class ProfileUserLinksController
 * @package App\Http\Controllers
 */
class ProfileUserLinksController extends Controller
{
    /**
     * Lists profile-user links for a given view (profile).
     * (profileUserLinks.listManagementProfileUserLinks)
     *
     * @param string $accountId Account ID which the given view (profile) belongs
     * to.
     * @param string $webPropertyId Web Property ID which the given view (profile)
     * belongs to. Can either be a specific web property ID or '~all', which refers
     * to all the web properties that user has access to.
     * @param string $profileId View (Profile) ID to retrieve the profile-user links
     * for. Can either be a specific profile ID or '~all', which refers to all the
     * profiles that user has access to.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, $webPropertyId, $profileId, Request $request)
    {
        try {
            // returns instance of \Google_Service_Storage
            $analytics = Google::make('analytics');
            $profileUserLinks = $analytics->management_profileUserLinks->listManagementProfileUserLinks($accountId, $webPropertyId, $profileId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($profileUserLinks->getItems());
    }

    protected function printResults($profileUserLinks)
    {
        $data = [];
        foreach ($profileUserLinks as $profileFilterLink) {
            $data[] = [
                'id' => $profileFilterLink->getId(),
                'kind' => $profileFilterLink->getKind(),
                'selfLink' => $profileFilterLink->getSelfLink(),
                'entity' => $profileFilterLink->getEntity(),
                'userRef' => $profileFilterLink->getUserRef(),
                'permissions' => $profileFilterLink->getPermissions(),
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