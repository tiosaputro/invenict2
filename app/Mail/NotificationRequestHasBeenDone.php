<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
class NotificationRequestHasBeenDone extends Mailable implements ShouldQueue
{
    use Queueable;
    public $datadone;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datadone){
        $this->datadone = $datadone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->from('noreply@emp.id','NO REPLY')
                    ->subject('Notification Request Has Been Done')
                    ->view('emailNotificationRequestDone')
                    ->with(['datadone' => $this->datadone]);
                }
}
