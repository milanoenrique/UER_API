<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RestorePassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $dataMail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataMail)
    {
        $this->dataMail = $dataMail;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.restore_password')
            ->with(['dataMail' => $this->dataMail])
            ->subject($this->dataMail['user'].', recibiste un código de restauración para restablcer tu contraseña');
    }
}
