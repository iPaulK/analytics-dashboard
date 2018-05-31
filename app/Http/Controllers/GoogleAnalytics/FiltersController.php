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
 * Class FiltersController
 * @package App\Http\Controllers
 */
class FiltersController extends Controller
{
    /**
     * Lists all filters for an account (filters.listManagementFilters)
     *
     * @param string $accountId Account ID to retrieve filters for.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, Request $request)
    {
        try {
          // returns instance of \Google_Service_Storage
          $analytics = Google::make('analytics');
          $filters = $analytics->management_filters->listManagementFilters($accountId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($filters->getItems());
    }

    protected function printResults($filters)
    {
        $data = [];
        foreach ($filters as $filter) {
            $data[] = [
                'id' => $filter->getId(),
                'kind' => $filter->getKind(),
                'selfLink' => $filter->getSelfLink(),
                'accountId' => $filter->getAccountId(),
                'name' => $filter->getName(),
                'type' => $filter->getType(),
                'created' => $filter->getCreated(),
                'updated' => $filter->getUpdated(),
                'isUpdatedLastDay' => $this->isUpdatedLastDay($filter->getUpdated()),
                'isCreatedLastDay' => $this->isCreatedLastDay($filter->getCreated()),
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