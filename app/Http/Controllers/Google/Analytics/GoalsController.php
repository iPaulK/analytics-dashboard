<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\Goal;
use App\JsonApi\Transformer\Google\Analytics\GoalTransformer;
use App\JsonApi\Document\Google\Analytics\GoalsDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class GoalsController
 * @package App\Http\Controllers
 */
class GoalsController extends Controller
{
    /**
     * Get the list of Goals
     *
     * @param string $profileId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($profileId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = Goal::findByProfileId($profileId)
            ->latest('created_at')
            ->get()
            ->unique('profileId');
        return $jsonApi->respond()->ok($this->createGoalsDocument(), $data);
    }

    /**
     * Get the list of Goals
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $goalId
     * @param string $profileId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $goalId, $profileId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = Goal::findByGoalIdAndProfileId($goalId, $profileId)->paginate();
        return $jsonApi->respond()->ok($this->createGoalsDocument(), $data);
    }

    /**
     * Create links document
     *
     * @return GoalsDocument
     */
    protected function createGoalsDocument()
    {
        return new GoalsDocument($this->createGoalTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return GoalTransformer
     */
    protected function createGoalTransformer()
    {
        return new GoalTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}