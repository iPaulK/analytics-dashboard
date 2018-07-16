<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\ProfileFilterLink;
use App\JsonApi\Transformer\Google\Analytics\ProfileFilterLinkTransformer;
use App\JsonApi\Document\Google\Analytics\ProfileFilterLinksDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class ProfileFilterLinksController
 * @package App\Http\Controllers
 */
class ProfileFilterLinksController extends Controller
{
    /**
     * Get the list of Profile Filter Links
     *
     * @param string $profileId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index($profileId, Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = ProfileFilterLink::findByProfileId($profileId)
            ->latest('created_at')
            ->get()
            ->unique('profileId');
        return $jsonApi->respond()->ok($this->createProfileFilterLinksDocument(), $data);
    }

    /**
     * Get the list of Profile Filter Links
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $profileLinkId
     * @param string $profileId
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $profileLinkId, $profileId): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = ProfileFilterLink::findByProfileFilterLinkIdAndProfileId($profileLinkId, $profileId)->paginate();
        return $jsonApi->respond()->ok($this->createProfileFilterLinksDocument(), $data);
    }

    /**
     * Create links document
     *
     * @return ProfileFilterLinksDocument
     */
    protected function createProfileFilterLinksDocument()
    {
        return new ProfileFilterLinksDocument($this->createProfileFilterLinkTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return ProfileFilterLinkTransformer
     */
    protected function createProfileFilterLinkTransformer()
    {
        return new ProfileFilterLinkTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}