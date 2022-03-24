<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            'name' => 'Richio medio',
            'state' => 'In Corso',
            'description' => Str::random(30)
        ]);

        DB::table('courses')->insert([
            'name' => 'Richio Basso',
            'state' => 'Finito',
            'description' => Str::random(30)
        ]);
    }
}
