<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class CourseUserSeeder extends Seeder
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
            DB::table('course_user')->insert([
                'user_id' => 1,
                'course_id' => $i,
            ]);
        }
        

        for($i = 1; $i <11; $i++)
        {
            DB::table('course_user')->insert([
                'user_id' => 2,
                'course_id' => $i,
            ]);
        }

        for($i = 11; $i <21; $i++)
        {
            DB::table('course_user')->insert([
                'user_id' => 3,
                'course_id' => $i,
            ]);
        }

        for($i = 5; $i <16; $i++)
        {
            DB::table('course_user')->insert([
                'user_id' => 4,
                'course_id' => $i,
            ]);
        }

        for($i = 1; $i <21; $i++)
        {

            for($j = 5; $j <11; $j++)
            {
                DB::table('course_user')->insert([
                    'user_id' => $j,
                    'course_id' => $i,
                ]);
            }
            
        }
    }
}
