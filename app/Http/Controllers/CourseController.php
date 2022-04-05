<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Progress;

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
        $getUsersCourse = DB::table('progress')
        ->select('users.user_name', 'users.id', 'users.email', 'users.role_id')
        ->join('courses', 'courses.id', '=', 'progress.course_id')
        ->join('users', 'users.id','=', 'progress.user_id')
        ->where('courses.id','=', $id)
        ->where('step_id','=',1)
        ->where('users.role_id','!=','1') //per rimuovere i tutor
        ->get();
        return response()->json($getUsersCourse, 200);
    }

    function addUsersCourse(Request $request, $course_id){
        $course = Course::find($course_id);
        $count_student = 0;
        $count_teacher = 0;
        if($course)
        {
            foreach($request['users'] as $userid)
            {
                $user = User::find($userid);
                if($user->role_id == 3){
                    Progress::create([
                        'step_id' => 1,
                        'state' => config('enums.state.progres.1'),
                        'user_id' => $userid,
                        'course_id' => $course_id
                    ]);
                    $count_student += 1;
                }
                if($user->role_id == 1 || $user->role_id == 2){
                    Progress::create([
                        'user_id' => $userid,
                        'course_id' => $course_id
                    ]);
                    $count_teacher += 1;
                }

            }
            $response = ['message' => "sono stati inseriti $count_student studenti e $count_teacher docenti o tutor"];
            return response()->json($response, 200);
        }

        $response = ['message' => "non è stata trovata l'azienda"];
        return response()->json($response, 404);
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

    function editCourse(Request $request,$id){
        $changeValue = false;
        $name = $request['name'];
        $state = $request['state'];
        $description = $request['description'];
        if($name != null){
            DB::update("update courses set name = \"$name\" where id = $id");
            $changeValue = true;
        }
        if($state != null){
            DB::update("update courses set state = \"$state\" where id = $id");
            $changeValue = true;
        }
        if($request != null){
            DB::update("update courses set description = \"$description\" where id = $id");
            $changeValue = true;
        }
        if($changeValue == true){
            return response("I valori sono stati cambiati correttamente");
        }
        return response("Nessun valore è stato cambiato");

    }

    function deleteCourse($id){
        $course = Course::find($id);

        if($course){
            $course->delete();
            DB::delete('delete from progress where course_id = ?', [$id]);

            return response("Record deleted successfully");
        }
        return response("Corso non trovato", 404);
    }

}
