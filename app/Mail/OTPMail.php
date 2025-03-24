<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $otp_info;
    public function __construct($otp_info)
    {
        $this->otp_info = $otp_info;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OTP Verification',
        );
    }
    public function content(): Content
    {
        return new Content(
            view: 'otp',
            with:['otp_info'=>$this->otp_info],
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
