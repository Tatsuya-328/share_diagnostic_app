<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $viewName;
    public $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $params)
    {
        $this->viewName = $view;
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text($this->viewName, $this->params);
    }
}
