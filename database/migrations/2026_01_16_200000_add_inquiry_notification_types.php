<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notification_types')) {
            return;
        }

        $now = now();

        $types = [
            [
                'user_type' => 'customer',
                'type' => 'product_query_status_changed_customer',
                'name' => 'Inquiry Status Updated',
                'image' => null,
                'default_text' => 'Your inquiry status for [[product_name]] is now [[status]]',
                'status' => 1,
            ],
            [
                'user_type' => 'customer',
                'type' => 'product_query_replied_customer',
                'name' => 'Inquiry Replied',
                'image' => null,
                'default_text' => 'Seller replied to your inquiry for [[product_name]]',
                'status' => 1,
            ],
        ];

        foreach ($types as $row) {
            $exists = DB::table('notification_types')->where('type', $row['type'])->exists();
            if (!$exists) {
                DB::table('notification_types')->insert([
                    ...$row,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('notification_types')) {
            return;
        }

        DB::table('notification_types')
            ->whereIn('type', [
                'product_query_status_changed_customer',
                'product_query_replied_customer',
            ])
            ->delete();
    }
};

