<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_user')->insert([
            'user_id' => 1,
            'course_id' => 1,
        ]);

        DB::table('course_user')->insert([
            'user_id' => 1,
            'course_id' => 2,
        ]);

        DB::table('course_user')->insert([
            'user_id' => 2,
            'course_id' => 2,
        ]);

        DB::table('course_user')->insert([
            'user_id' => 3,
            'course_id' => 1,
        ]);
    }
}
