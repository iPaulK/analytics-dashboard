<?php

namespace App\Http\Controllers\GoogleAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use App\Facades\Google;

/**
 * Class WebPropertiesController
 * @package App\Http\Controllers
 */
class WebPropertiesController extends Controller
{
    /**
     * Get the list of webproperties
     *
     * @param string $accountId Account ID to retrieve web properties for. Can
     * either be a specific account ID or '~all', which refers to all the accounts
     * that user has access to.
     * @param Request $request
     * @return json
     */
    public function index($accountId, Request $request)
    {
        // returns instance of \Google_Service_Storage
        $analytics = Google::make('analytics');
        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_webproperties->listManagementWebproperties($accountId);
        return $this->printResults($accounts->getItems());
    }

    protected function printResults($items)
    {
        $data = [];
        foreach ($items as $account) {
            $data[] = [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'accountId' => $account->getAccountId(),
                'kind' => $account->getKind(),
                'selfLink' => $account->getSelfLink(),
                'created' => $account->getCreated(),
                'updated' => $account->getUpdated(),
                'websiteUrl' => $account->getWebsiteUrl(),
                'permissions' => $account->getPermissions(),
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