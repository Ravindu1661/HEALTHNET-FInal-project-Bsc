<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Foreign key checks disable
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear only users table (admin user create කරන්න නම් users table පමණක් clear කළොත් බස්)
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Common password
        $password = Hash::make('0771717599');

        // Admin user create කිරීම
        DB::table('users')->insert([
            'email'            => 'admin@healthnet.lk',
            'password'         => $password,
            'user_type'        => 'admin',
            'status'           => 'active',
            'email_verified_at'=> now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}
