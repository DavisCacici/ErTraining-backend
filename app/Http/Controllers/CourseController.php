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
        $tokenParts = explode(".", $token);  
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $courses = DB::table('course_user', 'cu')
        ->join('courses', 'cu.course_id', '=', 'courses.id')
        ->join('users', 'cu.user_id', '=', 'users.id')
        ->select('courses.id', 'courses.name', 'courses.state')
        ->where('users.id', '=', $jwtPayload->user_id)->get();
        $response = [$courses];

        return response()->json($response, 200);
    }
}
