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
        for($i = 51; $i < 100; $i++)
        {
            DB::table('progress')->insert([
                'step_id' => rand(1, 4),
                'state'=>config('enums.state.progres.'.rand(1, 4)),
                'course_user_id' => $i,
            ]);
        }
    }
}
