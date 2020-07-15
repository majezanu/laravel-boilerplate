<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use \App\User;
class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Manuel Zavala',
            'email' => 'majezanu@gmail.com',
            'password' => Hash::make('admin2125'),
            'type' => User::TYPES['developer']
        ]);
    }
}
