<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractUpdateMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $inputs;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        $this->inputs = $inputs;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Documento con ID NN000'.$this->inputs['order_id'].' actualizado | Notarionet')->markdown('emails.contractupdate')->with(['inputs'=> $this->inputs]);
    }
}
