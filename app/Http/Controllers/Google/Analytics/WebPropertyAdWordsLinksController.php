<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\EntityAdWordsLink;
use App\JsonApi\Transformer\Google\Analytics\EntityAdWordsLinkTransformer;
use App\JsonApi\Document\Google\Analytics\EntityAdWordsLinksDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class WebPropertyAdWordsLinksController
 * @package App\Http\Controllers
 */
class WebPropertyAdWordsLinksController extends Controller
{
    /**
     * Lists webProperty-AdWords links for a given web property.
     *
     * @param string $webPropertyId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($webPropertyId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $links */
        $links = EntityAdWordsLink::findByWebPropertyId($webPropertyId)
            ->latest('created_at')
            ->get()
            ->unique('entityAdWordsLinkId');
        return $jsonApi->respond()->ok($this->createEntityAdWordsLinksDocument(), $links);
    }

    /**
     * Get history of changes
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $webPropertyId
     * @param string $entityAdWordsLinkId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $webPropertyId, $entityAdWordsLinkId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $links */
        $links = EntityAdWordsLink::findByEntityAdWordsLinkId($entityAdWordsLinkId)->paginate();
        return $jsonApi->respond()->ok($this->createEntityAdWordsLinksDocument(), $links);
    }

    /**
     * Create EntityAdWordsLinks document
     *
     * @return EntityAdWordsLinksDocument
     */
    protected function createEntityAdWordsLinksDocument()
    {
        return new EntityAdWordsLinksDocument($this->createEntityAdWordsLinkTransformer());
    }

    /**
     * Create EntityAdWordsLink resource transformer
     *
     * @return EntityAdWordsLinkTransformer
     */
    protected function createEntityAdWordsLinkTransformer()
    {
        return new EntityAdWordsLinkTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}