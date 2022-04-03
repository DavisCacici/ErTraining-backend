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
}
