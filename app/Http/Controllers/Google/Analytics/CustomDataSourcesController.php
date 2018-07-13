<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\CustomDataSource;
use App\JsonApi\Transformer\Google\Analytics\CustomDataSourceTransformer;
use App\JsonApi\Document\Google\Analytics\CustomDataSourcesDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class CustomDataSourcesController
 * @package App\Http\Controllers
 */
class CustomDataSourcesController extends Controller
{
    /**
     * Get the list of Custom Data Sources
     *
     * @param string $webPropertyId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($webPropertyId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = CustomDataSource::findByWebPropertyId($webPropertyId)
            ->latest('created_at')
            ->get()
            ->unique('customDataSourceId');
        return $jsonApi->respond()->ok($this->createCustomDataSourcesDocument(), $data);
    }

    /**
     * Get the list of Custom Data Sources
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $customDataSourceId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $customDataSourceId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = CustomDataSource::findByCustomDataSourceId($customDataSourceId)->paginate();
        return $jsonApi->respond()->ok($this->createCustomDataSourcesDocument(), $data);
    }

    /**
     * Create links document
     *
     * @return CustomDataSourcesDocument
     */
    protected function createCustomDataSourcesDocument()
    {
        return new CustomDataSourcesDocument($this->createCustomDataSourceTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return CustomDataSourceTransformer
     */
    protected function createCustomDataSourceTransformer()
    {
        return new CustomDataSourceTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}