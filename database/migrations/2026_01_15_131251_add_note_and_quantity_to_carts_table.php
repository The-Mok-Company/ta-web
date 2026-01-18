<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {

            if (!Schema::hasColumn('carts', 'note')) {
                $table->string('note', 500)->nullable()->after('variation');
            }

            if (!Schema::hasColumn('carts', 'quantity')) {
                $table->decimal('quantity', 12, 3)->nullable()->after('note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {

            if (Schema::hasColumn('carts', 'note')) {
                $table->dropColumn('note');
            }

            if (Schema::hasColumn('carts', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};
