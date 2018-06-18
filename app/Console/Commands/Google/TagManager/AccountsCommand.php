<?php

namespace App\Console\Commands\Google\TagManager;

use App\Facades\Google;
use App\Models\Google\TagManager\Account;
use App\Console\Commands\Google\GoogleCommand;

class AccountsCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:tagmanager:accounts';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:tagmanager:accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all GTM Accounts that a user has access to.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tagManager = Google::make('tagManager');
        $accounts = $tagManager->accounts->listAccounts();

        foreach ($accounts as $account) {
            $newAccount = (new Account)->transform($account);
            $lastAccount = Account::findLastByAccountId($account->getAccountId());
            
            $version = $lastAccount ? $lastAccount->version + 1 : 1;
            if (!$lastAccount || ($lastAccount && $newAccount->isDiff($lastAccount))) {
                $newAccount->version = $version;
                $newAccount->save();
            }
        }
    }
}