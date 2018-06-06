<?php

namespace App\Console\Commands\Google\Analytics;

use App\Facades\Google;
use App\Models\Google\RequestQueue;
use App\Models\Google\Analytics\Account;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use Google_Service_Exception;
use Google_Service_Analytics_Account;
use Illuminate\Console\Command;

class AccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all accounts to which the user has access.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $analytics = Google::make('analytics');
        $managementAccounts = $analytics->management_accounts->listManagementAccounts();

        foreach ($managementAccounts->getItems() as $managementAccount) {
            $attributes = $this->prepareAttributes($managementAccount);
            $newAccount = (new Account)->createAccount($attributes);

            $lastAccount = Account::findLastByAccountId($managementAccount->getId());
            if (!$lastAccount) {
                $newAccount->save();
            } else if ($lastAccount && $newAccount->isDiff($lastAccount)) {
                $newAccount->version = $lastAccount->version + 1;
                $newAccount->save();
            }

            $commands = [
                [
                    'command' => 'google:analytics:entityUserLinks',
                    'params' => json_encode(['account_id' => $newAccount->account_id]),
                    'status' => RequestQueue::STATUS_PENDING,
                    'created_at' => Carbon::now()->toDateTimeString()
                ],
                [
                    'command' => 'google:analytics:filters',
                    'params' => json_encode(['account_id' => $newAccount->account_id]),
                    'status' => RequestQueue::STATUS_PENDING,
                    'created_at' => Carbon::now()->toDateTimeString()
                ],
                [
                    'command' => 'google:analytics:webproperties',
                    'params' => json_encode(['account_id' => $newAccount->account_id]),
                    'status' => RequestQueue::STATUS_PENDING,
                    'created_at' => Carbon::now()->toDateTimeString()
                ],
            ];

            RequestQueue::insert($commands);
        }
    }

    protected function prepareAttributes(Google_Service_Analytics_Account $account)
    {
        return [
            'account_id' => $account->getId(),
            'kind' => $account->getKind(),
            'selfLink' => $account->getSelfLink(),
            'name' => $account->getName(),
            //'permissions' => $account->getPermissions(),
            // 'created' => $account->getCreated(),
            // 'updated' => $account->getUpdated(),
            'starred' => $account->getStarred() ? true : false,
        ];
    }
}