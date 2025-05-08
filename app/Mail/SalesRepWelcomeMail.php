<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesRepWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $company;

    public function __construct($email, $password, $company)
    {
        $this->email = $email;
        $this->password = $password;
        $this->company = $company;
    }

    public function build()
    {
        return $this->subject('Welcome to Competitive Relocation')
            ->view('emails.salesrep_welcome')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
                'company' => $this->company,
            ]);
    }
} 