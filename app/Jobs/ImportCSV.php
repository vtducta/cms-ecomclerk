<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Imports\ProductFBAImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportCSV implements  ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $file_name;
    public function __construct($filename)
    {
        $this->file_name = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('begin handle job: ' .$this->job->getJobId() );
        //try{
            Excel::import(new ProductFBAImport($this->job->getJobId()),$this->file_name);
        //}catch (\Exception $e){
        //    Log::error('error throw exception here'.$e->getMessage());
        //}
        Log::info('end handle job: ' .$this->job->getJobId() );
    }
}
