<?php

namespace App\Mail;

use App\Models\AccountApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountOpeningCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(AccountApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Application Received: Light As Air Account Opening')
                    ->view('emails.account-opening-customer');
    }
}
