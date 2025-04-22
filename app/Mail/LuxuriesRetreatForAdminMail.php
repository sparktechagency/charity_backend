<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LuxuriesRetreatForAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $sender_data;
    public function __construct($sender_data)
    {
        $this->sender_data = $sender_data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Donate Art, Antique or Collectables',
        );
    }

    public function content(): Content
    {
        return new Content(
            view:'collecttable',
            with:['sender_data'=>$this->sender_data]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
