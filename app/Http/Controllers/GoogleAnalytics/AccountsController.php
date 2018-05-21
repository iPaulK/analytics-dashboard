<?php

namespace App\Http\Controllers\GoogleAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use App\Facades\Google;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{
	  /**
     * Get the list of accounts
     *
     * @return json
     */
    public function index()
    {
        // returns instance of \Google_Service_Storage
        $analytics = Google::make('analytics');
        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();
        return $this->printResults($accounts->getItems());
    }

    protected function printResults($items)
    {
        $data = [];
        foreach ($items as $account) {
            $data[] = [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'kind' => $account->getKind(),
                'selfLink' => $account->getSelfLink(),
                'starred' => $account->getStarred(),
                'created' => $account->getCreated(),
                'updated' => $account->getUpdated(),
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