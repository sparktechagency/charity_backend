<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationNotification extends Notification
{
    use Queueable;

    protected $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invoice' => $this->transaction['invoice'],
            'name' => $this->transaction['name'],
            'profile' => url('default/profile.png'),
            'amount' => $this->transaction['amount'],
            'message' => $this->transaction['name'] . ' has made a payment of £' . number_format($this->transaction['amount'], 2),
            'remark' => $this->transaction['remark'],
            'status' => $this->transaction['payment_status'],
        ];

    }
}
