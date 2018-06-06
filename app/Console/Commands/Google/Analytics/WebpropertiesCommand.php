<?php

namespace App\Console\Commands\Google\Analytics;

use Illuminate\Console\Command;

class WebpropertiesCommand extends Command
{
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
        
    }
}