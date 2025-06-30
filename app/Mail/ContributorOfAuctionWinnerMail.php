<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContributorOfAuctionWinnerMail extends Mailable
{
    use Queueable, SerializesModels;

     protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 You Are the Auction Winner!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'contributorOfAuctionWinner',
            with: ['data' => $this->data]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
