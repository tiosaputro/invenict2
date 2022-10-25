<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class NotificationRequestWaitingToBeReceivedByPersonnelIct extends Mailable implements ShouldQueue
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
        return $this->from('noreply@emp.id','NO REPLY')
                    ->subject('Notification Request Waiting To Be Received By Personnel ICT')
                    ->view('emailNotificationWaitingReceivedByPersonnel')
                    ->with(['ict' => $this->ict]);
                }
}
