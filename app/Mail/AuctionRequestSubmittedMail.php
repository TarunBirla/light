<?php

namespace App\Mail;

use App\Models\AuctionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuctionRequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $auctionRequest;

    public function __construct(AuctionRequest $auctionRequest)
    {
        $this->auctionRequest = $auctionRequest;
    }

    public function build()
    {
        $productTitle = $this->auctionRequest->auctionProduct->title ?? 'Auction Item';
        return $this->subject('New Auction Bid Request #' . $this->auctionRequest->id . ' - ' . $productTitle)
                    ->view('emails.auction-request-submitted');
    }
}
