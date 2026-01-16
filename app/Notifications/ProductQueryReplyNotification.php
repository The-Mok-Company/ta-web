<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductQueryReplyNotification extends Notification
{
    use Queueable;

    public $data;
    public $className;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->className = ProductQueryReplyNotification::class;
    }

    public function via($notifiable)
    {
        return [DbNotification::class];
    }

    public function toArray($notifiable)
    {
        return [
            'notification_type_id' => $this->data['notification_type_id'],
            'data' => [
                'product_query_id' => $this->data['product_query_id'] ?? null,
                'product_id'       => $this->data['product_id'] ?? null,
                'product_slug'     => $this->data['product_slug'] ?? null,
                'product_name'     => $this->data['product_name'] ?? null,
                'status'           => $this->data['status'] ?? null,
                'status_label'     => $this->data['status_label'] ?? null,
                'link'             => $this->data['link'] ?? null,
            ],
        ];
    }
}

