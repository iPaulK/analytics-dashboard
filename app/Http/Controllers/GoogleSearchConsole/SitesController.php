<?php

namespace App\Http\Controllers\GoogleSearchConsole;

use App\Http\Controllers\Controller;
use App\Exceptions\GoogleServiceException;
use Google_Service_Exception;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use App\Facades\Google;

/**
 * Class SitesController
 * @package App\Http\Controllers
 */
class SitesController extends Controller
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
            $webmasters = Google::make('webmasters');
            $sites = $webmasters->sites->listSites();
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($sites);
    }

    protected function printResults($sites)
    {
        $data = [];
        foreach ($sites as $site) {
            $data[] = [
                'permissionLevel' => $site->getPermissionLevel(),
                'siteUrl' => $site->getSiteUrl()
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