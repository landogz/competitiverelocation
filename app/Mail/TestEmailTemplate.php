<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $template;

    /**
     * Create a new message instance.
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    public function build()
    {
        return $this->subject($this->template->subject)
            ->view('emails.template')
            ->with([
                'content' => $this->template->content
            ]);
    }
}
