<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $notificationTypes = [
            // Customer notifications
            [
                'type' => 'inquiry_created_customer',
                'name' => 'Inquiry Created',
                'default_text' => 'Your inquiry [[inquiry_code]] has been submitted successfully.',
                'user_type' => 'customer',
                'status' => 1,
            ],
            [
                'type' => 'inquiry_status_changed_customer',
                'name' => 'Inquiry Status Changed',
                'default_text' => 'Your inquiry [[inquiry_code]] status has been changed to [[status]].',
                'user_type' => 'customer',
                'status' => 1,
            ],
            [
                'type' => 'inquiry_message_customer',
                'name' => 'New Message on Inquiry',
                'default_text' => 'You have a new message on your inquiry [[inquiry_code]].',
                'user_type' => 'customer',
                'status' => 1,
            ],

            // Admin notifications
            [
                'type' => 'inquiry_created_admin',
                'name' => 'New Inquiry Received',
                'default_text' => 'A new inquiry [[inquiry_code]] has been received from [[user_name]].',
                'user_type' => 'admin',
                'status' => 1,
            ],
            [
                'type' => 'inquiry_status_changed_admin',
                'name' => 'Inquiry Status Changed',
                'default_text' => 'Inquiry [[inquiry_code]] status has been changed to [[status]].',
                'user_type' => 'admin',
                'status' => 1,
            ],
            [
                'type' => 'inquiry_message_admin',
                'name' => 'New Message on Inquiry',
                'default_text' => 'You have a new message on inquiry [[inquiry_code]] from [[user_name]].',
                'user_type' => 'admin',
                'status' => 1,
            ],
        ];

        foreach ($notificationTypes as $type) {
            // Check if type already exists
            $exists = DB::table('notification_types')->where('type', $type['type'])->exists();
            if (!$exists) {
                $id = DB::table('notification_types')->insertGetId([
                    'type' => $type['type'],
                    'name' => $type['name'],
                    'default_text' => $type['default_text'],
                    'user_type' => $type['user_type'],
                    'status' => $type['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Add translation for default language
                DB::table('notification_type_translations')->insert([
                    'notification_type_id' => $id,
                    'lang' => config('app.locale', 'en'),
                    'name' => $type['name'],
                    'default_text' => $type['default_text'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $types = [
            'inquiry_created_customer',
            'inquiry_status_changed_customer',
            'inquiry_message_customer',
            'inquiry_created_admin',
            'inquiry_status_changed_admin',
            'inquiry_message_admin',
        ];

        foreach ($types as $type) {
            $notificationType = DB::table('notification_types')->where('type', $type)->first();
            if ($notificationType) {
                DB::table('notification_type_translations')
                    ->where('notification_type_id', $notificationType->id)
                    ->delete();
                DB::table('notifications')
                    ->where('notification_type_id', $notificationType->id)
                    ->delete();
                DB::table('notification_types')->where('id', $notificationType->id)->delete();
            }
        }
    }
};
