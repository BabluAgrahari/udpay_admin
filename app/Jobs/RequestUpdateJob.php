<?php

namespace App\Jobs;

use App\Models\VAccCollection;
use App\Models\AccountSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
// use romanzipp\QueueMonitor\Traits\IsMonitored;
use violetshih\MongoQueueMonitor\Traits\IsMonitored;


class RequestUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    protected $user_id, $id;
    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $id)
    {
        $this->user_id = $user_id;
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user_id = $this->user_id;
        $id = $this->id;

       
    }

}
