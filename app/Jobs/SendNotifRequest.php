<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationRequest;
use Illuminate\Support\Facades\Mail;

class SendNotifRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $send_mail;
    protected $ICT;
    protected $LINK;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($send_mail,$ICT,$LINK){
        $this->send_mail = $send_mail;
        $this->ICT = $ICT;
        $this->LINK = $LINK;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){     
        Mail::to($this->send_mail)->send(new NotificationRequest($this->ICT,$this->LINK));
    }
}
