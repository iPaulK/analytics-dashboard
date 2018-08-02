<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\JsonApi\Document\{
    UserDocument,
    UsersDocument
};
use App\JsonApi\Hydrator\UserHydrator;
use App\JsonApi\Transformer\UserResourceTransformer;
use App\Models\{
    User,
    Role
};
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * Get the list of users
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $users */
        $users = User::filter($request)
            ->paginate();
        return $jsonApi->respond()->ok($this->createUsersDocument(), $users);
    }

    /**
     * Get the user
     *
     * @param $id
     * @param JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show($id, JsonApi $jsonApi): ResponseInterface
    {
        $user = User::findOrFail($id);
        // Returns a "200 Ok" response
        return $jsonApi->respond()->ok($this->createUserDocument(), $user);
    }

    /**
     * Create new user
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        // Hydrating the retrieved employee object from the request
        $user = (new User)->createByRole(Role::ROLE_EMPLOYEE);
        // Hydrating the retrieved user object from the request
        $user = $jsonApi->hydrate(new UserHydrator(), $user);
        $user->save();
        // Returns a "201 Created" response
        return $jsonApi->respond()->ok($this->createUserDocument(), $user);
    }

    /**
     * Update the user
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, JsonApi $jsonApi, $id): ResponseInterface
    {
        $user = User::findOrFail($id);

        $accounts = $this->getAccountsIds($request);

        // Hydrating the retrieved user object from the request
        $user = $jsonApi->hydrate(new UserHydrator(), $user);
        $user->accounts()->sync($accounts);
        $user->save();

        // Returns a "200 Ok" response
        return $jsonApi->respond()->ok($this->createUserDocument(), $user);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getAccountsIds(Request $request)
    {
        $accounts = [];
        $data = $request->input('data');
        if (isset($data['attributes']['accounts_id']) && is_array($data['attributes']['accounts_id'])) {
            foreach ($data['attributes']['accounts_id'] as $accountId) {
                $accounts[] = $accountId;
            }
        }
        return $accounts;
    }

    /**
     * Delete the user
     *
     * @param $id
     * @param JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete($id, JsonApi $jsonApi)
    {
        $user = User::findOrFail($id);
        $user->delete();
        // Returns a "204 No Content" response.
        return $jsonApi->respond()->noContent();
    }

    /**
     * Create user document
     *
     * @return UserDocument
     */
    protected function createUserDocument()
    {
        return new UserDocument($this->createUserTransformer());
    }

    /**
     * Create users document
     *
     * @return UsersDocument
     */
    protected function createUsersDocument()
    {
        return new UsersDocument($this->createUserTransformer());
    }

    /**
     * Create users resource transformer
     *
     * @return UserResourceTransformer
     */
    protected function createUserTransformer()
    {
        return new UserResourceTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}
