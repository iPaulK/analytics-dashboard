<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\Profile;

class ProfilesCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:profiles';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:profiles {accountId} {webPropertyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all profiles for an account.';

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
        
        $profiles = $analytics->management_profiles->listManagementProfiles($accountId, $webPropertyId);
        
        foreach ($profiles->getItems() as $profile) {
            $newProfile = (new Profile)->transform($profile);
            
            $lastProfile = Profile::findLastByProfileId($profile->getId());
            
            $version = $lastProfile ? $lastProfile->version + 1 : 1;
            
            if (!$lastProfile || ($lastProfile && $newProfile->isDiff($lastProfile))) {
                $newProfile->version = $version;
                $newProfile->save();
            }

            $params = [
                'accountId' => $profile->getAccountId(),
                'webPropertyId' => $profile->getWebPropertyId(),
                'profileId' => $profile->getId(),
            ];

            (new GoalsCommand)->addToQueue($params);
            (new ProfileFilterLinksCommand)->addToQueue($params);
        }
    }
}