<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationRequestHasBeenDone;
use Mail;

class SendNotifDone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail;
    protected $ict;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail,$ict)
    {
        $this->mail = $mail;
        $this->ict = $ict;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {     
        Mail::to($this->mail)->send(new NotificationRequestHasBeenDone($this->ict));
    }
}
