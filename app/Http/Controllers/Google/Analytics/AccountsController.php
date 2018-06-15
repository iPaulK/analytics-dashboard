<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\Account;
use App\JsonApi\Transformer\Google\Analytics\AccountTransformer;
use App\JsonApi\Document\Google\Analytics\{
    AccountDocument,
    AccountsDocument
};
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{
    /**
     * Get the list of accounts
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $accounts */
        $accounts = Account::filter($request)
            ->latest('created_at')
            ->get()
            ->unique('accountId');
        return $jsonApi->respond()->ok($this->createAccountsDocument(), $accounts);
    }

    /**
     * Get the list of accounts
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $id
     * @return ResponseInterface
     */
    public function history(Request $request, JsonApi $jsonApi, $id): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $accounts */
        $accounts = Account::findByAccountId($id)->paginate();
        return $jsonApi->respond()->ok($this->createAccountsDocument(), $accounts);
    }

    /**
     * Create accounts document
     *
     * @return UsersDocument
     */
    protected function createAccountsDocument()
    {
        return new AccountsDocument($this->createAccountTransformer());
    }

    /**
     * Create account resource transformer
     *
     * @return AccountTransformer
     */
    protected function createAccountTransformer()
    {
        return new AccountTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}