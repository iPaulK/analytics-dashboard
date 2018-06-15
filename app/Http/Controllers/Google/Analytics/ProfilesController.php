<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\Profile;
use App\JsonApi\Transformer\Google\Analytics\ProfileTransformer;
use App\JsonApi\Document\Google\Analytics\{
    ProfileDocument,
    ProfilesDocument
};
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class ProfilesController
 * @package App\Http\Controllers
 */
class ProfilesController extends Controller
{
    /**
     * Get the list of profiles
     *
     * @param string $accountId
     * @param string $webPropertyId
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index(
      Request $request,
      JsonApi $jsonApi,
      $accountId,
      $webPropertyId
    ): ResponseInterface {
        /** @var \Illuminate\Support\Collection $profiles */
        $profiles = Profile::findByWebPropertyId($webPropertyId)
            ->latest('created_at')
            ->get()
            ->unique('profileId');
        return $jsonApi->respond()->ok($this->createProfilesDocument(), $profiles);
    }

    /**
     * Get the list of profiles
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $profileId
     * @return ResponseInterface
     */
    public function history(
      Request $request,
      JsonApi $jsonApi,
      $accountId,
      $webPropertyId,
      $profileId
    ): ResponseInterface {
        /** @var \Illuminate\Support\Collection $profiles */
        $profiles = Profile::findByProfileId($profileId)->paginate();
        return $jsonApi->respond()->ok($this->createProfilesDocument(), $profiles);
    }

    /**
     * Create profiles document
     *
     * @return ProfilesDocument
     */
    protected function createProfilesDocument()
    {
        return new ProfilesDocument($this->createProfileTransformer());
    }

    /**
     * Create profile resource transformer
     *
     * @return ProfileTransformer
     */
    protected function createProfileTransformer()
    {
        return new ProfileTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}