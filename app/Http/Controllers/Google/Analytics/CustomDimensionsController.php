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
 * Class CustomDimensionsController
 * @package App\Http\Controllers
 */
class CustomDimensionsController extends Controller
{
    /**
     * Lists custom dimensions to which the user has access.
     * (customDimensions.listManagementCustomDimensions)
     *
     * @param string $accountId Account ID for the custom dimensions to retrieve.
     * @param string $webPropertyId Web property ID for the custom dimensions to
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
            $webproperties = $analytics->management_customDimensions->listManagementCustomDimensions($accountId, $webPropertyId);
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