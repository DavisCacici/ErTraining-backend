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
        for($i = 1; $i <11; $i++)
        {
            DB::table('courses')->insert([
                'name' => 'Corso Sicurezza Base '. $i,
                'state' => config('enums.state.course.1'),
                'description' => Str::random(30)
            ]);
            DB::table('courses')->insert([
                'name' => 'Corso Sicurezza Avanzato '. $i,
                'state' => config('enums.state.course.1'),
                'description' => Str::random(30)
            ]);
            DB::table('courses')->insert([
                'name' => 'Corso Primo Soccorso '. $i,
                'state' => config('enums.state.course.1'),
                'description' => Str::random(30)
            ]);
        }



    }
}
