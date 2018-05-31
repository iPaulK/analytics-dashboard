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
        try {
            // returns instance of \Google_Service_Storage
            $analytics = Google::make('analytics');
            // Get the list of accounts for the authorized user.
            $accounts = $analytics->management_accounts->listManagementAccounts();
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
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
                'name' => $account->getName(),
                'permissions' => $account->getPermissions(),
                'created' => $account->getCreated(),
                'updated' => $account->getUpdated(),
                'starred' => $account->getStarred(),
                'isUpdatedLastDay' => $this->isUpdatedLastDay($account->getUpdated()),
                'isCreatedLastDay' => $this->isCreatedLastDay($account->getCreated()),
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