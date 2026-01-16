<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    /**
     * Create (or update) an admin account for local/dev usage.
     *
     * Configure via env:
     * - ADMIN_NAME
     * - ADMIN_EMAIL
     * - ADMIN_PASSWORD
     */
    public function run()
    {
        $name = env('ADMIN_NAME', 'Admin');
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'password');

        // Build data safely for different user table variants.
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ];

        if (Schema::hasColumn('users', 'email_verified_at')) {
            $data['email_verified_at'] = now();
        }

        if (Schema::hasColumn('users', 'user_type')) {
            $data['user_type'] = 'admin';
        }

        if (Schema::hasColumn('users', 'banned')) {
            $data['banned'] = 0;
        }

        if (Schema::hasColumn('users', 'balance')) {
            $data['balance'] = 0.00;
        }

        if (Schema::hasColumn('users', 'is_suspicious')) {
            $data['is_suspicious'] = 0;
        }

        if (Schema::hasColumn('users', 'referral_code') && empty($data['referral_code'])) {
            $data['referral_code'] = Str::random(10);
        }

        // Idempotent: update if exists, otherwise create.
        $user = User::updateOrCreate(
            ['email' => $email],
            $data
        );

        // Ensure permission exists + grant to the seeded admin so reports can be tested.
        // Project uses guard 'web' (see existing permissions in sqlupdates/shop.sql).
        $permission = Permission::firstOrCreate(
            ['name' => 'inquiries_report', 'guard_name' => 'web'],
            ['section' => 'reports']
        );
        $user->givePermissionTo($permission);

        // Clear permission cache so changes apply immediately.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

