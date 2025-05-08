<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesRepPasswordResetMail extends Mailable
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
        return $this->subject('Password Reset Successful')
            ->view('emails.salesrep_password_reset')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
                'company' => $this->company,
            ]);
    }
} 