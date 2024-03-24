<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationPersonnel;
use Illuminate\Support\Facades\Mail;

class SendNotifPersonnel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $send_mail;
    protected $dataPersonnel;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($send_mail,$dataPersonnel){
        $this->send_mail = $send_mail;
        $this->dataPersonnel = $dataPersonnel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){     
        Mail::to($this->send_mail)->send(new NotificationPersonnel($this->dataPersonnel));
    }
}
