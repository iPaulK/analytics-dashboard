<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\CustomDimension;
use App\JsonApi\Transformer\Google\Analytics\CustomDimensionTransformer;
use App\JsonApi\Document\Google\Analytics\CustomDimensionsDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class CustomDimensionsController
 * @package App\Http\Controllers
 */
class CustomDimensionsController extends Controller
{
    /**
     * Get the list of Custom Dimensions
     *
     * @param string $webPropertyId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($webPropertyId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = CustomDimension::findByWebPropertyId($webPropertyId)
            ->latest('created_at')
            ->get()
            ->unique('customDimensionId');
        return $jsonApi->respond()->ok($this->createCustomDimensionsDocument(), $data);
    }

    /**
     * Get the list of Custom Dimensions
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $customDimensionId
     * @param string $webPropertyId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $customDimensionId, $webPropertyId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = CustomDimension::findByCustomDimensionIdAndWebPropertyId($customDimensionId, $webPropertyId)->paginate();
        return $jsonApi->respond()->ok($this->createCustomDimensionsDocument(), $data);
    }

    /**
     * Create links document
     *
     * @return CustomDimensionsDocument
     */
    protected function createCustomDimensionsDocument()
    {
        return new CustomDimensionsDocument($this->createCustomDimensionTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return CustomDimensionTransformer
     */
    protected function createCustomDimensionTransformer()
    {
        return new CustomDimensionTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}