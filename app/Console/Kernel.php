<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Google\RequestQueueCommand::class,
        Commands\Google\Analytics\AccountsCommand::class,
        Commands\Google\Analytics\AccountUserLinksCommand::class,
        Commands\Google\Analytics\FiltersCommand::class,
        Commands\Google\Analytics\WebpropertiesCommand::class,
        Commands\Google\Analytics\ProfilesCommand::class,
        Commands\Google\Analytics\WebPropertyAdWordsLinksCommand::class,
        Commands\Google\Analytics\CustomDataSourcesCommand::class,

        Commands\Google\TagManager\AccountsCommand::class,

        Commands\Google\SearchConsole\SitesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
