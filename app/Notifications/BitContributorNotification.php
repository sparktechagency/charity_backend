<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BitContributorNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $contributor;
    public function __construct($contributor)
    {
        $this->contributor = $contributor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->contributor['id'],
            'name' => $this->contributor['name'],
            'profile' => url('default/profile.png'),
            'bit_online' => $this->contributor['bit_online'],
            'message' => $this->contributor['name'] . ' has made a payment of £' . number_format($this->contributor['amount'], 2),
            'remark' => $this->contributor['remark'],
            'status' => $this->contributor['payment_status'],
        ];
    }
}
