<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\ProfileFilterLink;

class ProfileFilterLinksCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:profilefilterlinks';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:profilefilterlinks {accountId} {webPropertyId} {profileId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all profile filter links for a profile.';

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

        $profileId = $this->argument('profileId');
        
        $links = $analytics->management_profileFilterLinks
                    ->listManagementProfileFilterLinks($accountId, $webPropertyId, $profileId);
        
        foreach ($links->getItems() as $link) {
            $new = (new ProfileFilterLink)->transform($link);
            
            $last = ProfileFilterLink::findLastByProfileFilterLinkIdAndProfileId($link->getId(), $profileId);
            
            $version = $last ? $last->version + 1 : 1;
            
            if (!$last || ($last && $new->isDiff($last))) {
                $new->version = $version;

                $new->accountId = $accountId;
                $new->webPropertyId = $webPropertyId;
                $new->profileId = $profileId;
                $new->filterId = $link->getFilterRef()->getId();

                $new->save();
            }
        }
    }
}