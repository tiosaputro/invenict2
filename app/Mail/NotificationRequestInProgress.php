<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class NotificationRequestInProgress extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
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
        return $this->from('icthelpdesk.admin@emp.id')
                    ->subject('Notification Request In Progress')
                    ->view('emailNotificationRequestInProgress')
                    ->with(['ict' => $this->ict]);
                }
}
