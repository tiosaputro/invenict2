<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

class SendNotifToRequestor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $to;
    protected $Ict;
    protected $body;
    protected $footer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to,$Ict,$body,$footer){
        $this->to = $to;
        $this->Ict = $Ict;
        $this->body = $body;
        $this->footer = $footer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){     
        Mail::to($this->to)->send(new NotificationMail($this->Ict,$this->body,$this->footer));
    }
}
