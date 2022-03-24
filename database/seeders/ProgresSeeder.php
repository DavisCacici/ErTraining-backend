<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProgresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('progres')->insert([
            'step_id' => 1,
            'state'=>'Finito',
            'course_user_id' => 3,
        ]);

        DB::table('progres')->insert([
            'step_id' => 2,
            'state'=>'Finito',
            'course_user_id' => 3,
        ]);

        DB::table('progres')->insert([
            'step_id' => 3,
            'state'=>'In corso',
            'course_user_id' => 3,
        ]);
    }
}
