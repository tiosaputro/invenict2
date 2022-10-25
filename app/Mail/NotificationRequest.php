<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class NotificationRequest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $LINK;
    public $ict;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ict,$LINK)
    {
        $this->ict = $ict;
        $this->LINK = $LINK;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@emp.id','HELPDESK TICKETING')
                    ->view('emailRequest')
                    ->with(['ict' => $this->ict,
                            'link'=>$this->LINK]);
                }
}
