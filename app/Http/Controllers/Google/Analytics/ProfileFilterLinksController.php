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
 * Class ProfileFilterLinksController
 * @package App\Http\Controllers
 */
class ProfileFilterLinksController extends Controller
{
    /**
     * Lists all profile filter links for a profile.
     * (profileFilterLinks.listManagementProfileFilterLinks)
     *
     * @param string $accountId Account ID to retrieve profile filter links for.
     * @param string $webPropertyId Web property Id for profile filter links for.
     * Can either be a specific web property ID or '~all', which refers to all the
     * web properties that user has access to.
     * @param string $profileId Profile ID to retrieve filter links for. Can either
     * be a specific profile ID or '~all', which refers to all the profiles that
     * user has access to.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, $webPropertyId, $profileId, Request $request)
    {
        try {
            // returns instance of \Google_Service_Storage
            $analytics = Google::make('analytics');
            $profileFilterLinks = $analytics->management_profileFilterLinks->listManagementProfileFilterLinks($accountId, $webPropertyId, $profileId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($profileFilterLinks->getItems());
    }

    protected function printResults($profileFilterLinks)
    {
        $data = [];
        foreach ($profileFilterLinks as $profileFilterLink) {
            $data[] = [
                'id' => $profileFilterLink->getId(),
                'kind' => $profileFilterLink->getKind(),
                'selfLink' => $profileFilterLink->getSelfLink(),
                'rank' => $profileFilterLink->getRank(),
                'profileRef' => $profileFilterLink->getProfileRef(),
                'filterRef' => $profileFilterLink->getFilterRef(),
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