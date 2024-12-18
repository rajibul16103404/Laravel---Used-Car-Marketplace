<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auths')->insert([
            [
                'name' => 'User 1',
                'email' => 'user1@xample.com',
                'password' => Hash::make('password123'),
                'phone' => '1111111',
                'role' => 0,
                
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@xample.com',
                'password' => Hash::make('password123'),
                'phone' => '11111112',
                'role' => 0,
            ],
            [
                'name' => 'Admin1',
                'email' => 'admin1@xample.com',
                'password' => Hash::make('adminpassword'),
                'phone' => '11111115',
                'role' => 1,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@xample.com',
                'password' => Hash::make('adminpassword'),
                'phone' => '11111113',
                'role' => 1,
            ],
        ]);
    }
}
