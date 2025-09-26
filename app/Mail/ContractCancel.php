<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractCancel extends Mailable
{
    use Queueable, SerializesModels;
 protected $inputs;
    protected $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs,$pdf)
    {
        $this->inputs = $inputs;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('El documento de NOTARIONET. ID NN000'.$this->inputs['order_id'].' ha sido cancelado | Notarionet')->markdown('emails.contractcancel')->with(['inputs'=> $this->inputs]);
                /*->attachData($this->pdf, "Contrato.pdf",[
                        'mime' => 'application/pdf'
                    ]);*/
    }
}
