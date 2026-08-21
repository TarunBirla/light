<?php

namespace App\Mail;

use App\Models\EquipmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EquipmentRequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $equipmentRequest;

    public function __construct(EquipmentRequest $equipmentRequest)
    {
        $this->equipmentRequest = $equipmentRequest;
    }

    public function build()
    {
        return $this->subject('New Equipment Request #' . $this->equipmentRequest->id . ' - ' . ($this->equipmentRequest->production_title ?? 'No Title'))
                    ->view('emails.equipment-request-submitted');
    }
}
