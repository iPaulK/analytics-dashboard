<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\EntityUserLink;

class AccountUserLinksCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:entityUserLinks';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:entityUserLinks {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists account-user links for a given account.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $analytics = Google::make('analytics');
        $account_id = $this->argument('account_id');
        $accountUserLinks = $analytics->management_accountUserLinks->listManagementAccountUserLinks($account_id);

        foreach ($accountUserLinks->getItems() as $accountUserLink) {
            $newUserLink = (new EntityUserLink)->transform($accountUserLink);
            $newUserLink->accountId = $account_id;
            
            $lastUserLink = EntityUserLink::findLastByUserLinkId($accountUserLink->getId());
            
            $version = $lastUserLink ? $lastUserLink->version + 1 : 1;
            
            if (!$lastUserLink || ($lastUserLink && $newUserLink->isDiff($lastUserLink))) {
                $newUserLink->version = $version;
                $newUserLink->save();
            }
        }
    }
}