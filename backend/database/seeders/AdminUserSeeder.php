<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create cart for admin
        $admin->cart()->create();

        // Create a picker user
        $picker = User::create([
            'name' => 'Picker User',
            'email' => 'picker@picker.com',
            'password' => Hash::make('password'),
            'role' => 'picker',
            'email_verified_at' => now(),
        ]);

        // Create cart for picker
        $picker->cart()->create();

        // Create a test customer
        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@customer.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Create cart for customer
        $customer->cart()->create();

        $this->command->info('Admin, picker, and customer users created successfully!');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('Picker: picker@picker.com / password');
        $this->command->info('Customer: customer@customer.com / password');
    }
}
