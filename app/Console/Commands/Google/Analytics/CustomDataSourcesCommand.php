<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\CustomDataSource;

class CustomDataSourcesCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:customdatasources';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:customdatasources {accountId} {webPropertyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List custom data sources to which the user has access.';

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

        $customDataSources = $analytics->management_customDataSources
                                ->listManagementCustomDataSources($accountId, $webPropertyId);
        
        foreach ($customDataSources->getItems() as $customDataSource) {
            $new = (new CustomDataSource)->transform($customDataSource);
            
            $last = CustomDataSource::findLastByCustomDataSourceId($customDataSource->getId());
            
            $version = $last ? $last->version + 1 : 1;
            
            if (!$last || ($last && $new->isDiff($last))) {
                $new->version = $version;
                $new->save();
            }
        }
    }
}