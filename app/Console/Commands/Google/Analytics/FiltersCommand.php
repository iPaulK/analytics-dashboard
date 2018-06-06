<?php

namespace App\Console\Commands\Google\Analytics;

use Illuminate\Console\Command;
use Exception;

class FiltersCommand extends Command
{
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
        
    }
}