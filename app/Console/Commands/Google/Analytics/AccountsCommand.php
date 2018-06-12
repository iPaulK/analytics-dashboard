<?php

namespace App\Console\Commands\Google\Analytics;

use App\Facades\Google;
use App\Models\Google\Analytics\Account;
use App\Console\Commands\Google\GoogleCommand;

class AccountsCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:accounts';

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
        $accounts = $analytics->management_accounts->listManagementAccounts();

        foreach ($accounts->getItems() as $account) {
            $newAccount = (new Account)->transform($account);
            $lastAccount = Account::findLastByAccountId($account->getId());
            
            $version = $lastAccount ? $lastAccount->version + 1 : 1;
            if (!$lastAccount || ($lastAccount && $newAccount->isDiff($lastAccount))) {
                $newAccount->version = $version;
                $newAccount->save();
            }

            $params = ['account_id' => $account->getId()];

            (new AccountUserLinksCommand)->addToQueue($params);
            (new FiltersCommand)->addToQueue($params);
            (new WebpropertiesCommand)->addToQueue($params);
        }
    }
}