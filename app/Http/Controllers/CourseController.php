<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $user = User::where('token', $token)->first();
        $courses = DB::table('course_user', 'cu')
        ->join('courses', 'cu.course_id', '=', 'courses.id')
        ->join('users', 'cu.user_id', '=', 'users.id')
        ->select('courses.id', 'courses.name', 'courses.state')
        ->where('users.id', '=', $user->id)->get();
        $response = ['user' => $user, 'courses'=>$courses];
        // foreach($courses as $course)
        // {
        //     $response[] = $course;
        // }
        return response()->json($response, 200);
    }
}
