<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\WebProperty;
use App\JsonApi\Transformer\Google\Analytics\WebPropertyTransformer;
use App\JsonApi\Document\Google\Analytics\{
    WebPropertyDocument,
    WebPropertiesDocument
};
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class WebPropertiesController
 * @package App\Http\Controllers
 */
class WebPropertiesController extends Controller
{
    /**
     * Get the list of properties
     *
     * @param string $accountId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($accountId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $properties */
        $properties = WebProperty::findByAccountId($accountId)
            ->latest('created_at')
            ->get()
            ->unique('webpropertyId');
        return $jsonApi->respond()->ok($this->createWebPropertiesDocument(), $properties);
    }

    /**
     * Get the list of properties
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $webPropertyId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $webPropertyId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $properties */
        $properties = WebProperty::findByWebPropertyId($webPropertyId)->paginate();
        return $jsonApi->respond()->ok($this->createWebPropertiesDocument(), $properties);
    }

    /**
     * Create properties document
     *
     * @return WebPropertiesDocument
     */
    protected function createWebPropertiesDocument()
    {
        return new WebPropertiesDocument($this->createWebPropertyTransformer());
    }

    /**
     * Create property resource transformer
     *
     * @return WebPropertyTransformer
     */
    protected function createWebPropertyTransformer()
    {
        return new WebPropertyTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}