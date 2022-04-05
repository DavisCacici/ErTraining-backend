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
        $state = 1;
        for($i = 5; $i <= 10; $i++)
        {
            for($j=1; $j <= 20; $j++){
                for($x=1; $x<=4; $x++){
                    if($x==1){
                        DB::table('progress')->insert([
                            'step_id' => $x,
                            'user_id' => $i,
                            'course_id' => $j,
                            'state'=>config('enums.state.progres.'.(4)),
                        ]);
                    }
                    else if ($x==2){
                        DB::table('progress')->insert([
                            'step_id' => $x,
                            'user_id' => $i,
                            'course_id' => $j,
                            'state'=>config('enums.state.progres.'.(3)),
                        ]);
                    }
                    else {
                        DB::table('progress')->insert([
                            'step_id' => $x,
                            'user_id' => $i,
                            'course_id' => $j,
                            'state'=>config('enums.state.progres.'.(1)),
                        ]);
                    }

                }
            }

        }

        for($x=0; $x<=70;$x++){
            $user= rand(5,10);
            $course = rand(1,20);
            DB::delete("delete from progress where user_id = $user  AND course_id = $course");
        }
    }
}
