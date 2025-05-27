<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Rolan',
            'last_name' => 'Benavidez',
            'email' => 'rolan.benavidez@gmail.com',
            'password' => Hash::make('0000000'),
            'email_verified_at' => now(),
        ]);
    }
}
