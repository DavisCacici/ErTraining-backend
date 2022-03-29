<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_name' => 'Davis',
            'email' => 'antoninodaviscacici@gmail.com',
            'password' => Hash::make('password'),
            'role_id'=> 1
        ]);
        DB::table('users')->insert([
            'user_name' => 'Melania',
            'email' => 'melaniatizzi@gmail.com',
            'password' => Hash::make('password'),
            'role_id'=> 2
        ]);
        DB::table('users')->insert([
            'user_name' => 'Alex',
            'email' => 'alexburgagni@gmail.com',
            'password' => Hash::make('password'),
            'role_id'=> 2
        ]);
        DB::table('users')->insert([
            'user_name' => 'Leonardo',
            'email' => 'leonardogaruti@gmail.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
        DB::table('users')->insert([
            'user_name' => 'Marco',
            'email' => 'marcovilla@gmail.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
    }
}
