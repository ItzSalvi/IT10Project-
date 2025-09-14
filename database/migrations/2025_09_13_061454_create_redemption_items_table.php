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
        Schema::create('redemption_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('redemption_id')->constrained()->onDelete('cascade');
        $table->foreignId('reward_id')->constrained()->onDelete('cascade');
        $table->integer('quantity');
        $table->integer('cost'); // total cost of this reward (quantity * reward cost)
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemption_items');
    }
};
