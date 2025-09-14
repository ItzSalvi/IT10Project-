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
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['item_type', 'points']);
            $table->integer('points_per_bottle')->default(10)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('item_type')->after('transaction_id');
            $table->integer('points')->after('quantity');
            $table->dropColumn('points_per_bottle');
        });
    }
};