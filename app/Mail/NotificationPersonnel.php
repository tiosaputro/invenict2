<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
class NotificationPersonnel extends Mailable implements ShouldQueue
{
    use Queueable;
    public $dataPersonnel;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataPersonnel){
        $this->dataPersonnel = $dataPersonnel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->from('noreply@emp.id','NO REPLY')
                    ->view('emailPersonnel')
                    ->with(['dataPersonnel' => $this->dataPersonnel]);
                }
}
