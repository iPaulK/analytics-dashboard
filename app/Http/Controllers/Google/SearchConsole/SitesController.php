<?php

namespace App\Http\Controllers\Google\SearchConsole;

use App\Http\Controllers\Controller;
use App\Models\Google\SearchConsole\Site;
use App\JsonApi\Transformer\Google\SearchConsole\SiteTransformer;
use App\JsonApi\Document\Google\SearchConsole\{
    SiteDocument,
    SitesDocument
};
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * Class SitesController
 * @package App\Http\Controllers
 */
class SitesController extends Controller
{
    /**
     * Get the list of sites
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        $currentUser = JWTAuth::user();
        $query = Site::query();

        if ($currentUser->role->isEmployee()) {
            $siteUrls = []; // TODO
            $query->whereIn('siteUrl', $siteUrls);
        }
        /** @var \Illuminate\Support\Collection $sites */
        $sites = Site::filter($request, $query)
            ->latest('created_at')
            ->get()
            ->unique('siteUrl');
        return $jsonApi->respond()->ok($this->createSitesDocument(), $sites);
    }

    /**
     * Get the list of sites
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $siteUrl
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $siteUrl): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $sites */
        $sites = Site::findBySiteUrl($siteUrl)->paginate();
        return $jsonApi->respond()->ok($this->createSitesDocument(), $sites);
    }

    /**
     * Create accounts document
     *
     * @return SitesDocument
     */
    protected function createSitesDocument()
    {
        return new SitesDocument($this->createSiteTransformer());
    }

    /**
     * Create site resource transformer
     *
     * @return SiteTransformer
     */
    protected function createSiteTransformer()
    {
        return new SiteTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}