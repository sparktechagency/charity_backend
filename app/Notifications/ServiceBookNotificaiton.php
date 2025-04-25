<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceBookNotificaiton extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'name' => $this->booking->name,
            'email' => $this->booking->email,
            'profile' => url('deafult/profile.png'),
            'message' => 'A new service booking has been made by ' . $this->booking->name . '.',
            'telephone_number' => $this->booking->telephone_number,
            'book_date' => $this->booking->book_date,
            'book_time' => $this->booking->book_time,
            'status' => $this->booking->book_status,
        ];
    }
}
