<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('steps')->insert([
            'step' => 'Step1',
            'description'=>Str::random(50),
        ]);
        DB::table('steps')->insert([
            'step' => 'Step2',
            'description'=>Str::random(50),
        ]);
        DB::table('steps')->insert([
            'step' => 'Step3',
            'description'=>Str::random(50),
        ]);
        DB::table('steps')->insert([
            'step' => 'Step4',
            'description'=>Str::random(50),
        ]);
    }
}
