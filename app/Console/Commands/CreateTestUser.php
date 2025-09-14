<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateTestUser extends Command
{
    protected $signature = 'user:create-test';
    protected $description = 'Create a test user for Arduino integration';

    public function handle()
    {
        // Check if user already exists
        $user = User::where('email', 'test@arduino.com')->first();
        
        if ($user) {
            $this->info("Test user already exists with ID: {$user->id}");
            return;
        }

        // Create test user
        $user = User::create([
            'fname' => 'Arduino',
            'mname' => 'Test',
            'lname' => 'User',
            'email' => 'test@arduino.com',
            'password' => bcrypt('password'),
            'total_points' => 0,
            'role' => 'user'
        ]);

        $this->info("Test user created successfully!");
        $this->info("User ID: {$user->id}");
        $this->info("Email: {$user->email}");
        $this->info("Password: password");
    }
}