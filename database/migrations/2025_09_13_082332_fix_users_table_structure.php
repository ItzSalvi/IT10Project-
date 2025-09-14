<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, copy any existing data from name to fname if fname is empty
        DB::statement('UPDATE users SET fname = name WHERE fname IS NULL OR fname = ""');
        DB::statement('UPDATE users SET lname = name WHERE lname IS NULL OR lname = ""');
        
        // Drop the old columns
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('users', 'old_points')) {
                $table->dropColumn('old_points');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
        });
        
        DB::statement('UPDATE users SET name = fname');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fname', 'mname', 'lname', 'total_points']);
        });
    }
};