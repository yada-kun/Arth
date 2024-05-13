<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user = User::create([
            'name' => 'sample',
            'email' => 'sample1@mail.com',
            'password' => bcrypt('password'),
         ]);

        $user->assignRole('admin');
    }
}
