<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InquiryNotification extends Notification
{
    use Queueable;

    public $data;
    public $className;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->className = InquiryNotification::class;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DbNotification::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notification_type_id' => $this->data['notification_type_id'],
            'data' => [
                'inquiry_id'   => $this->data['inquiry_id'] ?? null,
                'inquiry_code' => $this->data['inquiry_code'] ?? null,
                'user_id'      => $this->data['user_id'] ?? null,
                'status'       => $this->data['status'] ?? null,
                'message'      => $this->data['message'] ?? null,
                'link'         => $this->data['link'] ?? null,
            ]
        ];
    }
}
