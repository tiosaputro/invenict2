<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationRequestRejectedByHigherLevel;
use Mail;

class SendNotifRejectByHigherLevel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email_address;
    protected $ICT;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_address,$ICT)
    {
        $this->email_address = $email_address;
        $this->ICT = $ICT;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {     
        Mail::to($this->email_address)->send(new NotificationRequestRejectedByHigherLevel($this->ICT));
    }
}
