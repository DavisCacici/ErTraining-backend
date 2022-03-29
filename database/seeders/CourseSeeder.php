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
        for($i = 0; $i <10; $i++)
        {
            DB::table('courses')->insert([
                'name' => Str::random(10),
                'state' => config('enums.state.course.2'),
                'description' => Str::random(30)
            ]);
            DB::table('courses')->insert([
                'name' => Str::random(10),
                'state' => config('enums.state.course.3'),
                'description' => Str::random(30)
            ]);
        }



    }
}
