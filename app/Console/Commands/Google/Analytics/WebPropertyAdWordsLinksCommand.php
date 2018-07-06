<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\EntityAdWordsLink;

class WebPropertyAdWordsLinksCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:webpropertyadwordslinks';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:webpropertyadwordslinks {accountId} {webPropertyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists webProperty-AdWords links for a given web property.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $analytics = Google::make('analytics');
        
        $accountId = $this->argument('accountId');

        $webPropertyId = $this->argument('webPropertyId');

        // returns instance of \Google_Service_Storage
        $analytics = Google::make('analytics');
        $webPropertyAdWordsLinks = $analytics->management_webPropertyAdWordsLinks->listManagementWebPropertyAdWordsLinks($accountId, $webPropertyId);
        
        foreach ($webPropertyAdWordsLinks->getItems() as $webPropertyAdWordsLink) {
            $new = (new EntityAdWordsLink)->transform($webPropertyAdWordsLink);
            
            $last = EntityAdWordsLink::findLastByEntityAdWordsLinkId($webPropertyAdWordsLink->getId());
            
            $version = $last ? $last->version + 1 : 1;
            
            if (!$last || ($last && $new->isDiff($last))) {
                $new->version = $version;
                $new->save();
            }
        }
    }
}