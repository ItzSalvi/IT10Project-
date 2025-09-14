<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'johnpaul@admin.com';
        $password = 'password123';
        
        // Check if admin user exists
        $admin = User::where('email', $email)->first();
        
        if ($admin) {
            // Update existing user to admin
            $admin->update([
                'role' => 'admin',
                'fname' => 'John Paul',
                'mname' => '',
                'lname' => 'Salvana',
                'password' => Hash::make($password),
                'total_points' => 0
            ]);
            $this->info("Admin user updated: {$email}");
        } else {
            // Create new admin user
            User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'fname' => 'John Paul',
                'mname' => '',
                'lname' => 'Salvana',
                'total_points' => 0
            ]);
            $this->info("Admin user created: {$email}");
        }
        
        $this->info("Password: {$password}");
        $this->info("Role: admin");
        $this->info("Full name: John Paul Salvana");
    }
}