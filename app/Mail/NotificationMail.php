<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable;
    protected $Ict;
    protected $body;
    protected $footer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Ict,$body,$footer){
        $this->Ict = $Ict;
        $this->body = $body;
        $this->footer = $footer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->from('noreply@emp.id','NO REPLY')
                    ->view('emailNotificationMail')
                    ->with('ict', $this->Ict);
                }
}
