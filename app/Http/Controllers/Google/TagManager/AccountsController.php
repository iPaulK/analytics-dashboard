<?php

namespace App\Http\Controllers\Google\TagManager;

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
            $tagManager = Google::make('tagManager');
            // Get the list of accounts for the authorized user.
            $accounts = $tagManager->accounts->listAccounts();
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($accounts);
    }

    protected function printResults($accounts)
    {
        $data = [];
        foreach ($accounts as $account) {
            $data[] = [
                'accountId' => $account->getAccountId(),
                'fingerprint' => $account->getFingerprint(),
                'name' => $account->getName(),
                'path' => $account->getPath(),
                'shareData' => $account->getShareData(),
                'tagManagerUrl' => $account->getTagManagerUrl(),
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