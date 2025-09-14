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
        Schema::table('rewards', function (Blueprint $table) {
            $table->renameColumn('cost', 'points_req');
            $table->integer('stock')->default(0)->after('points_req');
            $table->boolean('status')->default(true)->after('stock');
            $table->text('description')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->renameColumn('points_req', 'cost');
            $table->dropColumn(['stock', 'status', 'description']);
        });
    }
};