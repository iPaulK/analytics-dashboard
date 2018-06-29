<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\EntityUserLink;
use App\JsonApi\Transformer\Google\Analytics\EntityUserLinkTransformer;
use App\JsonApi\Document\Google\Analytics\EntityUserLinksDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class AccountUserLinksController
 * @package App\Http\Controllers
 */
class AccountUserLinksController extends Controller
{
    /**
     * Get the list of User Links
     *
     * @param string $accountId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($accountId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $links */
        $links = EntityUserLink::findByAccountId($accountId)
            ->latest('created_at')
            ->get()
            ->unique('userLinkId');
        return $jsonApi->respond()->ok($this->createEntityUserLinksDocument(), $links);
    }

    /**
     * Get the list of accounts
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $userLinkId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $userLinkId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $links */
        $links = EntityUserLink::findByUserLinkId($userLinkId)->paginate();
        return $jsonApi->respond()->ok($this->createEntityUserLinksDocument(), $links);
    }

    /**
     * Create links document
     *
     * @return UsersDocument
     */
    protected function createEntityUserLinksDocument()
    {
        return new EntityUserLinksDocument($this->createEntityUserLinkTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return EntityUserLinkTransformer
     */
    protected function createEntityUserLinkTransformer()
    {
        return new EntityUserLinkTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}