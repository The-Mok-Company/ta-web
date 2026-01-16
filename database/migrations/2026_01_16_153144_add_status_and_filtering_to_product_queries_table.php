<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_queries', function (Blueprint $table) {
            $table->string('status')->default('new')->after('reply');
            $table->unsignedInteger('category_id')->nullable()->after('product_id');
            $table->timestamp('expires_at')->nullable()->after('status');
            $table->index('status');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_queries', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id']);
            $table->dropColumn(['status', 'category_id', 'expires_at']);
        });
    }
};
