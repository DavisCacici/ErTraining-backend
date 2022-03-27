<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // DB::table('users', 'a');
        $token = $request->bearerToken();
        $user = User::where('token', $token)->first();
        $courses = User::find($user->id)->courses()->get();
        return response()->json($courses, 200);
    }
}
