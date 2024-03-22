<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
class NotificationRequestInProgress extends Mailable implements ShouldQueue
{
    use Queueable;
    public $ict;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ict)
    {
        $this->ict = $ict;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@emp.id','NO REPLY')
                    ->subject('Notification Request In Progress')
                    ->view('emailNotificationRequestInProgress')
                    ->with('ict', $this->ict);
                }
}
