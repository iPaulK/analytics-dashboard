<?php

namespace App\Console\Commands\Google\Analytics;

use App\Console\Commands\Google\GoogleCommand;
use App\Facades\Google;
use App\Models\Google\Analytics\Filter;

class FiltersCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:analytics:filters';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:analytics:filters {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all filters for an account.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $analytics = Google::make('analytics');

        $account_id = $this->argument('account_id');

        $filters = $analytics->management_filters->listManagementFilters($account_id);
        
        foreach ($filters->getItems() as $filter) {
            $newFilter = (new Filter)->transform($filter);
            
            $lastFilter = Filter::findLastByFilterId($filter->getId());
            
            $version = $lastFilter ? $lastFilter->version + 1 : 1;
            
            if (!$lastFilter || ($lastFilter && $newFilter->isDiff($lastFilter))) {
                $newFilter->version = $version;
                $newFilter->save();
            }
        }
    }
}