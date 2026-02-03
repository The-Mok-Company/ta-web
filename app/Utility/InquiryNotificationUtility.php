<?php

namespace App\Utility;

use App\Models\User;
use App\Models\Inquiry;
use App\Notifications\InquiryNotification;
use Illuminate\Support\Facades\Notification;

class InquiryNotificationUtility
{
    /**
     * Send notification when a new inquiry is created by customer
     */
    public static function sendInquiryCreatedNotification(Inquiry $inquiry)
    {
        // Notify customer
        $customer = $inquiry->user;
        if ($customer) {
            $notificationType = get_notification_type('inquiry_created_customer', 'type');
            if ($notificationType && $notificationType->status == 1) {
                $data = [
                    'notification_type_id' => $notificationType->id,
                    'inquiry_id' => $inquiry->id,
                    'inquiry_code' => $inquiry->code,
                    'user_id' => $inquiry->user_id,
                    'status' => $inquiry->status,
                    'message' => 'Your inquiry has been submitted successfully.',
                    'link' => route('inquiries.show', $inquiry->id),
                ];
                Notification::send($customer, new InquiryNotification($data));
            }
        }

        // Notify admin(s)
        $admins = User::where('user_type', 'admin')->get();
        foreach ($admins as $admin) {
            $notificationType = get_notification_type('inquiry_created_admin', 'type');
            if ($notificationType && $notificationType->status == 1) {
                $data = [
                    'notification_type_id' => $notificationType->id,
                    'inquiry_id' => $inquiry->id,
                    'inquiry_code' => $inquiry->code,
                    'user_id' => $inquiry->user_id,
                    'status' => $inquiry->status,
                    'message' => 'A new inquiry has been received from ' . ($customer->name ?? 'Unknown'),
                    'link' => route('admin.inquiries.show', $inquiry->id),
                ];
                Notification::send($admin, new InquiryNotification($data));
            }
        }
    }

    /**
     * Send notification when inquiry status changes
     */
    public static function sendStatusChangedNotification(Inquiry $inquiry, string $oldStatus)
    {
        // Only send if status actually changed
        if ($oldStatus === $inquiry->status) {
            return;
        }

        // Notify customer
        $customer = $inquiry->user;
        if ($customer) {
            $notificationType = get_notification_type('inquiry_status_changed_customer', 'type');
            if ($notificationType && $notificationType->status == 1) {
                $data = [
                    'notification_type_id' => $notificationType->id,
                    'inquiry_id' => $inquiry->id,
                    'inquiry_code' => $inquiry->code,
                    'user_id' => $inquiry->user_id,
                    'status' => $inquiry->status,
                    'message' => 'Your inquiry status has been changed to ' . ucfirst($inquiry->status),
                    'link' => route('inquiries.show', $inquiry->id),
                ];
                Notification::send($customer, new InquiryNotification($data));
            }
        }

        // Notify admin(s) if customer changed status
        if (auth()->user() && auth()->user()->user_type === 'customer') {
            $admins = User::where('user_type', 'admin')->get();
            foreach ($admins as $admin) {
                $notificationType = get_notification_type('inquiry_status_changed_admin', 'type');
                if ($notificationType && $notificationType->status == 1) {
                    $data = [
                        'notification_type_id' => $notificationType->id,
                        'inquiry_id' => $inquiry->id,
                        'inquiry_code' => $inquiry->code,
                        'user_id' => $inquiry->user_id,
                        'status' => $inquiry->status,
                        'message' => 'Inquiry status changed to ' . ucfirst($inquiry->status),
                        'link' => route('admin.inquiries.show', $inquiry->id),
                    ];
                    Notification::send($admin, new InquiryNotification($data));
                }
            }
        }
    }

    /**
     * Send notification when a message/note is added to inquiry
     */
    public static function sendMessageNotification(Inquiry $inquiry, string $senderType)
    {
        if ($senderType === 'admin') {
            // Admin sent message -> notify customer
            $customer = $inquiry->user;
            if ($customer) {
                $notificationType = get_notification_type('inquiry_message_customer', 'type');
                if ($notificationType && $notificationType->status == 1) {
                    $data = [
                        'notification_type_id' => $notificationType->id,
                        'inquiry_id' => $inquiry->id,
                        'inquiry_code' => $inquiry->code,
                        'user_id' => $inquiry->user_id,
                        'status' => $inquiry->status,
                        'message' => 'You have a new message on your inquiry.',
                        'link' => route('inquiries.show', $inquiry->id),
                    ];
                    Notification::send($customer, new InquiryNotification($data));
                }
            }
        } else {
            // Customer sent message -> notify admin(s)
            $admins = User::where('user_type', 'admin')->get();
            $senderName = auth()->user()->name ?? 'Customer';

            foreach ($admins as $admin) {
                $notificationType = get_notification_type('inquiry_message_admin', 'type');
                if ($notificationType && $notificationType->status == 1) {
                    $data = [
                        'notification_type_id' => $notificationType->id,
                        'inquiry_id' => $inquiry->id,
                        'inquiry_code' => $inquiry->code,
                        'user_id' => $inquiry->user_id,
                        'status' => $inquiry->status,
                        'message' => 'New message from ' . $senderName . ' on inquiry.',
                        'link' => route('admin.inquiries.show', $inquiry->id),
                    ];
                    Notification::send($admin, new InquiryNotification($data));
                }
            }
        }
    }
}
