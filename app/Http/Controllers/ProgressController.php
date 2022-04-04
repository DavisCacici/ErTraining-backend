<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    function index($id)
    {
        $progres = DB::table('progress')
                ->join('steps', 'progress.step_id', '=', 'steps.id')
                ->join('course_user', 'progress.course_user_id', '=', 'course_user.id')
                ->join('courses', 'course_user.course_id', '=', 'courses.id')
                ->join('users', 'course_user.user_id', '=', 'users.id')
                ->select(['steps.step', 'progress.state', 'users.user_name','users.email', 'courses.name'])
                ->where('courses.id', '=', $id)
                ->where('users.role_id', '=', 3)->get();

        // dd($progres);
        return response()->json($progres, 200);
    }

    function getProgress($id){
        $getProgress = DB::table('progress')
        ->select('progress.step_id','progress.state','users.user_name', 'users.id', 'users.email', 'users.role_id')
        ->join('courses', 'courses.id', '=', 'progress.course_id')
        ->join('users', 'users.id','=', 'progress.user_id')
        ->where('courses.id','=', $id)
        ->where('users.role_id','!=','1') //per rimuovere i tutor
        ->get();
        return response()->json($getProgress, 200);
    }

    function setStateProgress(Request $request, $id){
        $state = $request['state'];
        DB::update("update progress set state = \"$state\" where id = $id");
        if($state=="Finito"){
            $value = DB::table('progress')
            ->select('progress.step_id', 'progress.user_id','progress.course_id')
            ->where('progress.id', '=', $id)->get();
            $value = json_decode($value, true);
            $step_id = $value[0]{'step_id'};
            if($step_id == 4){
                return response("Non ci sono più giochi");
            }
            $step_id = $step_id + 1;
            $user_id = $value[0]{'user_id'};
            $course_id = $value[0]{'course_id'};

            DB::table('progress')
            ->insert([
            'step_id' => $step_id,
            'user_id' => $user_id,
            'course_id'=> $course_id,
            'state'=>config('enums.state.progres.1'),
                ]);
            }
            return response("Stato aggiornato a Finito, creato nuovo stato di progresso");
        return response("Stato aggiornato", 200);
    }
}
