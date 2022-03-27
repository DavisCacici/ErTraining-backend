<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'tutor',
            'description' => 'creare un corso
            avviare un corso
            creare utenti di qualsiasi ruolo
            aggiunge utenti ai corsi
            togliere utenti ai corsi',
        ]);
        DB::table('roles')->insert([
            'name' => 'teacher',
            'description' => 'può vedere solo i corsi in cui insegna
            gli step degli student iscritti ai suoi corsi
            può abilitare l’avvio di un gioco',
        ]);
        DB::table('roles')->insert([
            'name' => 'student',
            'description' => 'può vedere solo i corsi a cui è stato abilitato
            può procedere al gioco solo se quest’ultimo è stato abilitato',
        ]);
    }
}
