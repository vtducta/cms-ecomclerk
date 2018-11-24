<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\Job as LaravelJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\Jobs;
//use Log;
use Illuminate\Support\Facades\Log;

class HandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * @param LaravelJob $job
     * @param array $data
     */
    public function handle(LaravelJob $job)
    {
		$file = '/home/ecomclerk/dev.ecomclerk.com/customLogs/logs_'.time().'.txt';
		file_put_contents($file,print_r($this->data, true));
        Log::info($job->getRawBody());
        // This is incoming JSON payload, already decoded to an array

    }
}

