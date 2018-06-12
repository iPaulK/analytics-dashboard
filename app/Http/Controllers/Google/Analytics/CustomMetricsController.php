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
 * Class CustomMetricsController
 * @package App\Http\Controllers
 */
class CustomMetricsController extends Controller
{
    /**
     * Lists custom metrics to which the user has access.
     * (customMetrics.listManagementCustomMetrics)
     *
     * @param string $accountId Account ID for the custom metrics to retrieve.
     * @param string $webPropertyId Web property ID for the custom metrics to
     * retrieve.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, $webPropertyId, Request $request)
    {
        try {
            // returns instance of \Google_Service_Storage
            $analytics = Google::make('analytics');
            $webproperties = $analytics->management_customMetrics->listManagementCustomMetrics($accountId, $webPropertyId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($webproperties->getItems());
    }

    protected function printResults($webproperties)
    {
        $data = [];
        foreach ($webproperties as $webproperty) {
            $data[] = [
                'id' => $webproperty->getId(),
                'kind' => $webproperty->getKind(),
                'selfLink' => $webproperty->getSelfLink(),
                'accountId' => $webproperty->getAccountId(),
                'webPropertyId' => $webproperty->getWebPropertyId(),
                'name' => $webproperty->getName(),
                'index' => $webproperty->getIndex(),
                'scope' => $webproperty->getScope(),
                'active' => $webproperty->getActive(),
                'type' => $webproperty->getType(),
                'min_value' => $webproperty->getMinValue(),
                'max_value' => $webproperty->getMaxValue(),
                'created' => $webproperty->getCreated(),
                'updated' => $webproperty->getUpdated(),
                'isUpdatedLastDay' => $this->isUpdatedLastDay($webproperty->getUpdated()),
                'isCreatedLastDay' => $this->isCreatedLastDay($webproperty->getCreated()),
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