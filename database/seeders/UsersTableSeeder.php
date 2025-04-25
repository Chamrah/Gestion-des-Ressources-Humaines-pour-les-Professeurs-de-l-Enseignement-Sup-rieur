<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'yahya',
            'last_name' => 'zaizi',
            'username' => 'yahyamoderator9',
            'email' => 'yahya15@gmail.com', // You can change this to a valid email address
            'password' => Hash::make('yahya2006'),
            'phone_number' => '0620973651', // You can change this to a valid phone number
            'role' => 'moderator',
        ]);
    }
}
