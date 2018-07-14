<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\CustomMetric;
use App\JsonApi\Transformer\Google\Analytics\CustomMetricTransformer;
use App\JsonApi\Document\Google\Analytics\CustomMetricsDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class CustomMetricsController
 * @package App\Http\Controllers
 */
class CustomMetricsController extends Controller
{
    /**
     * Get the list of Custom Metrics
     *
     * @param string $webPropertyId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($webPropertyId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = CustomMetric::findByWebPropertyId($webPropertyId)
            ->latest('created_at')
            ->get()
            ->unique('customMetricId');
        return $jsonApi->respond()->ok($this->createCustomMetricsDocument(), $data);
    }

    /**
     * Get the list of Custom Metrics
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $customMetricId
     * @param string $webPropertyId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $customMetricId, $webPropertyId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = CustomMetric::findByCustomMetricIdAndWebPropertyId($customMetricId, $webPropertyId)->paginate();
        return $jsonApi->respond()->ok($this->createCustomMetricsDocument(), $data);
    }

    /**
     * Create links document
     *
     * @return CustomMetricsDocument
     */
    protected function createCustomMetricsDocument()
    {
        return new CustomMetricsDocument($this->createCustomMetricTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return CustomMetricTransformer
     */
    protected function createCustomMetricTransformer()
    {
        return new CustomMetricTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}