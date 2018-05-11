<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\JsonApi\Document\{
    AccessTokenDocument, UserDocument
};
use App\JsonApi\Transformer\{
    AccessTokenResourceTransformer, UserResourceTransformer
};
use App\Exceptions\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as IlluminateAuth;
use Psr\Http\Message\ResponseInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param JsonApi $jsonApi
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws AuthenticationException
     */
    public function login(Request $request, JsonApi $jsonApi)
    {
        $credentials = $this->getCredentials($request);
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new AuthenticationException();
        }
        return $this->respondWithToken($jsonApi, $token);
    }

    /**
     * Get the authenticated User.
     *
     * @param JsonApi $jsonApi
     * @param UserDocument $document
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAuthenticatedUser(JsonApi $jsonApi, UserDocument $document)
    {
        return $jsonApi->respond()->ok($document, JWTAuth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout(JsonApi $jsonApi)
    {
        IlluminateAuth::guard()->logout();
        return $jsonApi->respond()->noContent();
    }

    /**
     * Refresh a token.
     *
     * @param JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function refresh(JsonApi $jsonApi)
    {
        return $this->respondWithToken($jsonApi, IlluminateAuth::guard()->refresh());
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        if ($attr = $request->input('data')['attributes']) {
            return [
                "email" => $attr['email'],
                "password" => $attr['password']
            ];
        }
        return [];
    }

    /**
     * Get the token array structure.
     *
     * @param JsonApi $jsonApi
     * @param  string $token
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function respondWithToken(JsonApi $jsonApi, $token)
    {
        $document = new AccessTokenDocument(
            new AccessTokenResourceTransformer()
        );
        return $jsonApi->respond()->ok($document, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}
