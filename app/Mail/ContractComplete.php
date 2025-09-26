<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractComplete extends Mailable
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
        return $this->subject('Documento con ID NN000'.$this->inputs['order_id'].' completado | Notarionet')->markdown('emails.contractcomplete')->with(['inputs'=> $this->inputs])
                ->attachData($this->pdf, "documento.pdf",[
                        'mime' => 'application/pdf'
                    ]);
    }
}
