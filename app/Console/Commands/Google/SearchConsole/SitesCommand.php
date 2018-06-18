<?php

namespace App\Console\Commands\Google\SearchConsole;

use App\Facades\Google;
use App\Models\Google\SearchConsole\Site;
use App\Console\Commands\Google\GoogleCommand;

class SitesCommand extends GoogleCommand
{
    /**
     * @var string
     */
    protected $cmd = 'google:searchconsole:sites';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:searchconsole:sites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all GTM Accounts that a user has access to.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $webmasters = Google::make('webmasters');
        $sites = $webmasters->sites->listSites();
        foreach ($sites as $site) {
            $newSite = (new Site)->transform($site);
            $lastSite = Site::findLastBySiteUrl($site->getSiteUrl());
            
            $version = $lastSite ? $lastSite->version + 1 : 1;
            if (!$lastSite || ($lastSite && $newSite->isDiff($lastSite))) {
                $newSite->version = $version;
                $newSite->save();
            }
        }
    }
}