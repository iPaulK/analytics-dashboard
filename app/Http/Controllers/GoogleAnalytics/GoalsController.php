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
 * Class GoalsController
 * @package App\Http\Controllers
 */
class GoalsController extends Controller
{
    /**
     * Lists goals to which the user has access. (goals.listManagementGoals)
     *
     * @param string $accountId Account ID to retrieve goals for. Can either be a
     * specific account ID or '~all', which refers to all the accounts that user has
     * access to.
     * @param string $webPropertyId Web property ID to retrieve goals for. Can
     * either be a specific web property ID or '~all', which refers to all the web
     * properties that user has access to.
     * @param string $profileId View (Profile) ID to retrieve goals for. Can either
     * be a specific view (profile) ID or '~all', which refers to all the views
     * (profiles) that user has access to.
     * @param Request $request
     *
     * @return json
     */
    public function index($accountId, $webPropertyId, $profileId, Request $request)
    {
        try {
            // returns instance of \Google_Service_Storage
            $analytics = Google::make('analytics');
            $goals = $analytics->management_goals->listManagementGoals($accountId, $webPropertyId, $profileId);
        } catch (Google_Service_Exception $e) {
            throw new GoogleServiceException($e->getMessage());
        }
        return $this->printResults($goals->getItems());
    }

    protected function printResults($goals)
    {
        $data = [];
        foreach ($goals as $goal) {
            $data[] = [
                'id' => $goal->getId(),
                'kind' => $goal->getKind(),
                'selfLink' => $goal->getSelfLink(),
                'accountId' => $goal->getAccountId(),
                'webPropertyId' => $goal->getWebPropertyId(),
                'name' => $goal->getName(),
                'internalWebPropertyId' =>  $goal->getInternalWebPropertyId(),
                'profileId' =>  $goal->getProfileId(),
                'value' =>  $goal->getValue(),
                'active' => $goal->getActive(),
                'type' => $goal->getType(),
                'created' => $goal->getCreated(),
                'updated' => $goal->getUpdated(),
                'isUpdatedLastDay' => $this->isUpdatedLastDay($goal->getUpdated()),
                'isCreatedLastDay' => $this->isCreatedLastDay($goal->getCreated()),
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