<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exceptions\{
    AuthenticationException,
    TokenInvalidException,
    JsonApiException
};
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class PasswordController
 * @package App\Http\Controllers\Api
 */
class PasswordController extends Controller
{
    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function forgot(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        $email = $request->input('data')['attributes']['email'];

        /* @var User $user */
        $user = User::findByEmailOrFail($email);

        // Create a new password reset token for the user.
        $token = $user->createPasswordToken();

        // Send the password reset notification.
        $user->sendPasswordResetNotification($token);

        return $jsonApi->respond()->noContent();
    }

    /**
     * Reset the given user's password.
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @throws AuthenticationException|TokenInvalidException|JsonApiException
     * @return ResponseInterface
     */
    public function reset(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        $params = $request->input('data')['attributes'];

        $credentials = [
            'email' => $params['email'],
            'password' => $params['password'],
            'password_confirmation' => $params['password'],
            'token' => $params['token'],
        ];

        $response = Password::broker('users')->reset($credentials, function (User $user, $password) {
            $user->password = app('hash')->make($password);
            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                // Returns a "200 Ok" response
                return $jsonApi->respond()->genericSuccess(200);
            case Password::INVALID_TOKEN:
                throw new TokenInvalidException();
            case Password::INVALID_USER:
            case Password::INVALID_PASSWORD:
                throw new AuthenticationException();
            default:
                throw new JsonApiException();
        }
    }
}
