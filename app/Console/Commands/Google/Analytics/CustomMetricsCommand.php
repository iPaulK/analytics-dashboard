<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\CustomMetric;

class CustomMetricsCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:custommetrics';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:custommetrics {accountId} {webPropertyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists custom metrics to which the user has access.';

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

        $customMetrics = $analytics->management_customMetrics
                                ->listManagementCustomMetrics($accountId, $webPropertyId);
        
        foreach ($customMetrics->getItems() as $customMetric) {
            $new = (new CustomMetric)->transform($customMetric);
            
            $last = CustomMetric::findLastByCustomMetricIdAndWebPropertyId($customMetric->getId(), $webPropertyId);
            
            $version = $last ? $last->version + 1 : 1;
            
            if (!$last || ($last && $new->isDiff($last))) {
                $new->version = $version;
                $new->save();
            }
        }
    }
}