<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->unique()->index();

            // ✅ خليها unsignedBigInteger (الأصح مع users default)
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->text('note')->nullable();

            // draft / submitted / ongoing / closed .. الخ
            $table->string('status')->default('draft')->index();

            $table->decimal('products_total', 14, 2)->default(0);
            $table->decimal('categories_total', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);

            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('delivery', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('extra_fees', 14, 2)->default(0);

            $table->decimal('total', 14, 2)->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
