<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiry_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inquiry_id');
            $table->enum('type', ['product','category'])->index();

            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();

            $table->unsignedInteger('quantity')->default(1);
            $table->string('unit')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('inquiry_id')->references('id')->on('inquiries')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiry_items');
    }
};
