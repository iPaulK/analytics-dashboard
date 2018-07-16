<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\CustomDimension;

class CustomDimensionsCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:customdimensions';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:customdimensions {accountId} {webPropertyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists custom dimensions to which the user has access.';

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

        $customDimensions = $analytics->management_customDimensions
                                ->listManagementCustomDimensions($accountId, $webPropertyId);
        
        foreach ($customDimensions->getItems() as $customDimension) {
            $new = (new CustomDimension)->transform($customDimension);
            
            $last = CustomDimension::findLastByCustomDimensionIdAndWebPropertyId($customDimension->getId(), $webPropertyId);
            
            $version = $last ? $last->version + 1 : 1;
            
            if (!$last || ($last && $new->isDiff($last))) {
                $new->version = $version;
                $new->save();
            }
        }
    }
}