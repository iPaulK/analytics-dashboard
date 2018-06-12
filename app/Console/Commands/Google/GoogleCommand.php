<?php

namespace App\Console\Commands\Google;

use App\Models\Google\RequestQueue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GoogleCommand extends Command
{
    public function addToQueue($params = [], $status = RequestQueue::STATUS_PENDING)
    {
        RequestQueue::create([
            'command' => $this->cmd,
            'params' => json_encode($params),
            'status' => $status,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}