<?php

namespace Database\Seeders;

use App\Models\Admins;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admins::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'), // Use a secure password here
            'role' => 'super_admin', // Add other fields as necessary
        ]);
    }
}
