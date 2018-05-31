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
 * Class AccountUserLinksController
 * @package App\Http\Controllers
 */
class AccountUserLinksController extends Controller
{
    /**
     * Lists account-user links for a given account.
     * (accountUserLinks.listManagementAccountUserLinks)
     *
     * @param string $accountId Account ID to retrieve the user links for.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, Request $request)
    {
        try {
            $analytics = Google::make('analytics');
            $accountUserLinks = $analytics->management_accountUserLinks->listManagementAccountUserLinks($accountId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($accountUserLinks->getItems());
    }

    protected function printResults($accountUserLinks)
    {
        $data = [];
        foreach ($accountUserLinks as $accountUserLink) {
            $data[] = [
                'id' => $accountUserLink->getId(),
                'kind' => $accountUserLink->getKind(),
                'selfLink' => $accountUserLink->getSelfLink(),
                'entity' => $accountUserLink->getEntity(),
                'userRef' => $accountUserLink->getUserRef(),
                'permissions' => $accountUserLink->getPermissions(),
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