<?php

namespace App\Jobs;

use App\Models\LevelCount;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use violetshih\MongoQueueMonitor\Traits\IsMonitored;

class RegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    protected $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        Log::info('run after 60 seconds.');
        $payload = (object) $this->payload;
        $insertLvl = $this->insertLvl($payload->unm);
        if (!$insertLvl) {
            Log::warning('Something went wrong, Not insert in User Lvl - UserId', [$payload->user_id]);
        } else {
            Log::info('Lvl created - UserId', [$payload->user_id]);
            $user = User::where('user_id', $payload->user_id)->first();
            $user->uflag = 5;
            $user->save();
        }
    }

    private function insertLvl($child_id)
    {
        $temp = $child_id;
        $lvl = 1;
        $refid = $child_id;
        while ($refid != '' || $refid != '0') {
            if ($refid >= 1) {
                $posid = User::select('ref_id')->where('user_id', $temp)->first();
                $refid = $posid->ref_id;

                $save = new LevelCount();
                $save->child_id = $child_id;
                $save->parent_id = $refid;
                $save->level = $lvl;
                $save->is_active = 0;
                if ($save->save()) {
                    $lvl = $lvl + 1;
                    $temp = $refid;
                    if ($temp == 0) {
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } 
    }
}
