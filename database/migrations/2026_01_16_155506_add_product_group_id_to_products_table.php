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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_group_id')->nullable()->after('brand_id');
            $table->foreign('product_group_id')->references('id')->on('product_groups')->onDelete('set null');
            $table->index('product_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_group_id']);
            $table->dropIndex(['product_group_id']);
            $table->dropColumn('product_group_id');
        });
    }
};
