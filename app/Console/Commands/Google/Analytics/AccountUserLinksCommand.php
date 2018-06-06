<?php

namespace App\Console\Commands\Google\Analytics;

use App\Facades\Google;
use Illuminate\Console\Command;

class AccountUserLinksCommand extends Command
{
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
        $accountUserLinks = $analytics->management_accountUserLinks->listManagementAccountUserLinks($this->argument('account_id'));

        foreach ($accountUserLinks->getItems() as $accountUserLink) {
            $attributes = $this->prepareAttributes($accountUserLink);
            
            var_dump($attributes);
        }
    }

    protected function prepareAttributes(Google_Service_Analytics_Account $accountUserLink)
    {
        return [
            'id' => $accountUserLink->getId(),
            'kind' => $accountUserLink->getKind(),
            'selfLink' => $accountUserLink->getSelfLink(),
            'entity' => $accountUserLink->getEntity(),
            'userRef' => $accountUserLink->getUserRef(),
            'permissions' => $accountUserLink->getPermissions(),
        ];
    }
}