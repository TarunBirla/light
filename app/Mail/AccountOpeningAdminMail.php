<?php

namespace App\Mail;

use App\Models\AccountApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountOpeningAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(AccountApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('New Account Opening Application #' . $this->application->id . ' - ' . $this->application->full_legal_name)
                    ->view('emails.account-opening-admin');
    }
}
