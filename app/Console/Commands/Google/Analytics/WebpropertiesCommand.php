<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\WebProperty;

class WebpropertiesCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:webproperties';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:webproperties {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists properties to which the user has access.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $analytics = Google::make('analytics');

        $account_id = $this->argument('account_id');

        $webproperties = $analytics->management_webproperties->listManagementWebproperties($account_id);
        foreach ($webproperties->getItems() as $webproperty) {
            $newWebproperty = (new WebProperty)->transform($webproperty);
            
            $lastWebproperty = WebProperty::findLastByWebPropertyId($webproperty->getId());
            
            $version = $lastWebproperty ? $lastWebproperty->version + 1 : 1;
            
            if (!$lastWebproperty || ($lastWebproperty && $newWebproperty->isDiff($lastWebproperty))) {
                $newWebproperty->version = $version;
                $newWebproperty->save();
            }

            $params = [
                'accountId' => $webproperty->getAccountId(),
                'webPropertyId' => $webproperty->getId(),
            ];

            (new ProfilesCommand)->addToQueue($params);
            (new WebPropertyAdWordsLinksCommand)->addToQueue($params);
        }
    }
}