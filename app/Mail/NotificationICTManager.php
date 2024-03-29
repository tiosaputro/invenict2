<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
class NotificationICTManager extends Mailable implements ShouldQueue
{
    use Queueable;
    public $ict;
    public $LINK;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ict,$LINK){
        $this->ict = $ict;
        $this->LINK = $LINK;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->from('noreply@emp.id','NO REPLY')
                    ->view('emailIctManager')
                    ->with(['ict' => $this->ict,
                            'link'=> $this->LINK]);
                }
}
