<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class NotificationPersonnel extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $dataPersonnel;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataPersonnel)
    {
        $this->dataPersonnel = $dataPersonnel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@emp.id','NO REPLY')
                    ->view('emailPersonnel')
                    ->with(['dataPersonnel' => $this->dataPersonnel]);
                }
}
