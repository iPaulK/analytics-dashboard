<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\Goal;

class GoalsCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:goals';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:goals {accountId} {webPropertyId} {profileId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists goals to which the user has access.';

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
        
        $goals = $analytics->management_goals
        				->listManagementGoals($accountId, $webPropertyId, $profileId);
        
        foreach ($goals->getItems() as $goal) {
            $new = (new Goal)->transform($goal);
            
            $last = Goal::findLastByGoalIdAndProfileId($goal->getId(), $goal->getProfileId());
            
            $version = $last ? $last->version + 1 : 1;
            
            if (!$last || ($last && $new->isDiff($last))) {
                $new->version = $version;
                $new->save();
            }
        }
    }
}