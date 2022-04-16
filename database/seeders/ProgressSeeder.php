<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <21; $i++)
        {
            DB::table('progress')->insert([
                'user_id' => 1,
                'course_id' => $i,
            ]);
        }


        for($i = 1; $i <11; $i++)
        {
            DB::table('progress')->insert([
                'user_id' => 2,
                'course_id' => $i,
            ]);
        }

        for($i = 11; $i <21; $i++)
        {
            DB::table('progress')->insert([
                'user_id' => 3,
                'course_id' => $i,
            ]);
        }

        for($i = 5; $i <16; $i++)
        {
            DB::table('progress')->insert([
                'step_id' => 1,
                'state' => config('enums.state.progres.1'),
                'user_id' => 4,
                'course_id' => $i,
            ]);
        }

        for($i = 1; $i <21; $i++)
        {

            for($j = 5; $j <11; $j++)
            {
                DB::table('progress')->insert([
                    'step_id' => 1,
                    'state' => config('enums.state.progres.1'),
                    'user_id' => $j,
                    'course_id' => $i,
                ]);
            }

        }
    }
}
