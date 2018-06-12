<?php

namespace App\Console\Commands\Google;

use App\Models\Google\RequestQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Exception;

class RequestQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:requestqueue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $schedule = RequestQueue::where('status', RequestQueue::STATUS_PENDING)
            ->orderBy('id', 'asc')
            ->take(5)
            ->get();

        if ($schedule->count() > 0) {
            foreach ($schedule as $item) {
                try {
                    $command = $item->command;
                    $params = json_decode($item->params, true);
                    
                    $result = Artisan::call('google:analytics:filters', $params);
                    $item->status = RequestQueue::STATUS_SUCCESS;
                    $item->messages = 'Successfully!';
                }  catch (Google_Service_Exception $e) {
                    $item->status = RequestQueue::STATUS_ERROR;
                    $item->messages = 'There was a Google API service error ' . $e->getCode() . ':' . $e->getMessage();
                } catch (Google_Exception $e) {
                    $item->status = RequestQueue::STATUS_ERROR;
                    $item->messages =  'There was a general API error ' . $e->getCode() . ':' . $e->getMessage();
                } catch (Exception $e) {
                    $item->status = RequestQueue::STATUS_ERROR;
                    $item->messages =  'There was error ' . $e->getCode() . ':' . $e->getMessage();
                }
                $item->save();
            }
        } else {
            $exitCode = Artisan::call('google:analytics:accounts');
        }
    }
}