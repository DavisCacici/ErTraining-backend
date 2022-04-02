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
            'email' => 'melania.tizzi.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 2
        ]);
        DB::table('users')->insert([
            'user_name' => 'Alex',
            'email' => 'alexandro.burgagni.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 2
        ]);
        DB::table('users')->insert([
            'user_name' => 'Leonardo',
            'email' => 'leonardo.garuti.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 2
        ]);
        DB::table('users')->insert([
            'user_name' => 'Marco',
            'email' => 'marco.villa.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
        DB::table('users')->insert([
            'user_name' => 'Marco',
            'email' => 'marco.scialla.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
        DB::table('users')->insert([
            'user_name' => 'Pietro',
            'email' => 'pietro.foschi.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
        DB::table('users')->insert([
            'user_name' => 'Eugenia',
            'email' => 'eugenia.facchini.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
        DB::table('users')->insert([
            'user_name' => 'Luca',
            'email' => 'luca.arcangeli.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
        DB::table('users')->insert([
            'user_name' => 'Davide',
            'email' => 'davide.guariento.studio@fitstic-edu.com',
            'password' => Hash::make('password'),
            'role_id'=> 3
        ]);
    }
}
