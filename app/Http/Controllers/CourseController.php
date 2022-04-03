<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

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
        ->select('courses.id', 'courses.name', 'courses.state', 'courses.description')
        ->where('users.id', '=', $jwtPayload->id)->get();
        $response = $courses;

        return response()->json($response, 200);
    }

    function coursesList(){
        $coursesList = DB::table('courses')
        ->select('courses.id', 'courses.name', 'courses.description')
        ->get();
        return response()->json($coursesList, 200);
    }

    function getCourse($id){
        $course = Course::find($id);

        if($course){
            $getCourse = DB::table('courses')
            ->select('courses.id', 'courses.name', 'courses.state','courses.description')
            ->where('courses.id', '=', $id)->get();

            return response()->json($getCourse, 200);
        }
        return response("corso non trovato",404);
    }

    function getUsersCourse($id){
        $getUsersCourse = DB::table('course_user')
        ->select('users.user_name', 'users.id', 'users.email', 'users.role_id')
        ->join('courses', 'courses.id', '=', 'course_user.course_id')
        ->join('users', 'users.id','=', 'course_user.user_id')
        ->where('courses.id','=', $id)
        ->where('users.role_id','!=','1') //per rimuovere i tutor
        ->get();
        return response()->json($getUsersCourse, 200);
    }

    function addCourse(Request $request){
        $name = $request['name'];
        $state = $request['state'];
        $description = $request['description'];

        DB::table('courses')
        ->insert([
            'name' => $name,
            'state' => $state,
            'description'=> $description
        ]);
        return response()->json("Corso creato con successo", 200);
    }


    function deleteCourse($id){
        $course = Course::find($id);

        if($course){
            $course->delete();
            DB::delete('delete from course_user where course_id = ?', [$id]);

            return response("Record deleted successfully");
        }
        return response("Corso non trovato", 404);
    }

}
