<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationRequestHasBeenDone;

class SendNotifDoneUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail;
    protected $datadone;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail,$datadone)
    {
        $this->mail = $mail;
        $this->datadone = $datadone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {     
        Mail::to($this->mail)->send(new NotificationRequestHasBeenDone($this->datadone));
    }
}
