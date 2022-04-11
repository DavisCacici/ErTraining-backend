<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProgressResource;
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
        $id = $jwtPayload->id;
        // $course = Progress::whereHas('user', function($query) use ($id){
        //     $query->where('id', $id);
        // })->get();
        $course = User::find($id)->courses;
        // dd($course);

        return CourseResource::collection($course);
        // $courses = DB::table('course_user', 'cu')
        // ->join('courses', 'cu.course_id', '=', 'courses.id')
        // ->join('users', 'cu.user_id', '=', 'users.id')
        // ->select('courses.id', 'courses.name', 'courses.state', 'courses.description')
        // ->where('users.id', '=', $jwtPayload->id)->get();
        // $response = $courses;

        // return response()->json($response, 200);
    }

    /**
    * @OA\Get(
    *   path="/api/courses/coursesList",
    *   summary="Tutor, - ritorna la lista dei corsi e la loro descrizione",
    *   description="Get the list of all courses",
    *   security={{ "apiAuth": {} }},
    *   operationId="coursesList",
    *   tags={"Courses"},
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function coursesList(){
        $course = Course::all();
        return CourseResource::collection($course);
    }

    /**
    * @OA\Get(
    *   path="/api/courses/getCourse/{id}",
    *   summary="Tutor - ritorna tutte le informazioni di un determinato corso",
    *   description="Get the course by id",
    *   operationId="getCourse",
    *   tags={"Courses"},
    *   security={{ "apiAuth": {} }},
    *   @OA\Parameter(
    *       description="course Id",
    *       in="path",
    *       name="id",
    *       required=true,
    *       @OA\Schema(
    *           type="integer",
    *           format="int64"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function getCourse($id){
        $course = Course::find($id);
        if(!$course)
        {
            return response("corso non trovato",404);
        }

        return new CourseResource($course);
    }

     /**
    * @OA\Get(
    *   path="/api/courses/getUsersCourse/{id}",
    *   summary="Ritorna tutte le informazioni degli utenti iscritti ad un corso",
    *   description="Get all the users of a course",
    *   operationId="getUsersCourse",
    *   tags={"Progress"},
    *   security={{ "apiAuth": {} }},
    *   @OA\Parameter(
    *       description="course Id",
    *       in="path",
    *       name="id",
    *       required=true,
    *       @OA\Schema(
    *           type="integer",
    *           format="int64"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function getUsersCourse($id){
        $getUsersCourse = DB::table('progress')
        ->select('users.user_name', 'users.id', 'users.email', 'users.role_id', "progress.id")
        ->join('courses', 'courses.id', '=', 'progress.course_id')
        ->join('users', 'users.id','=', 'progress.user_id')
        ->where('courses.id','=', $id)
        ->where('step_id','=',1)
        ->where('users.role_id','!=','1') //per rimuovere i tutor
        ->get();
        return response()->json($getUsersCourse, 200);
        // $progress = Progress::whereHas('course' function());
        // $users = User::whereHas('courses', function($query) use($id){
        //     $query->where('id', $id);
        // })->where('role_id', '!=', 1)->where('role_id', '!=', 2)->get();
        // $users = Course::find($id)->with('users', 'progress')->get();
        // $users = Progress::where('course_id', $id)->get();
        // ->users->where('role_id', '!=', 1)->where('role_id', '!=', 2);
        // return $users;
        // return ProgressResource::collection($users);
    }

 /**
 * @OA\Post(
 *      path="/api/courses/addUsersCourse/{course_id}",
 *      summary="Tutor - aggiunge utenti al corso",
 *      description="Add user to course",
 *      operationId="addUsersCourse",
 *      tags={"Progress"},
 *      security={{ "apiAuth": {} }},
 *      @OA\Parameter(
 *          description="course Id",
 *          in="path",
 *          name="course_id",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          description="User credentials",
 *          @OA\JsonContent(
 *              required={"user_id"},
 *              @OA\Property(
 *                  property="users",
 *                  type="array",
 *                  @OA\Items(
 *                      type="integer",
 *                      format="int64"
 *                  ),
 *              )
 *          )
 *     ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */

    function addUsersCourse(Request $request, $course_id){
        $request->validate([
            'users' => 'required|array',
        ]);
        $course = Course::find($course_id);
        if($course)
        {
            foreach($request['users'] as $userid)
            {
                $user = User::find($userid);
                if(!Progress::where('course_id', $course_id)->where('user_id', $userid)->exists())
                {
                    if($user->role_id == 3){
                        Progress::create([
                            'step_id' => 1,
                            'state' => config('enums.state.progres.1'),
                            'user_id' => $userid,
                            'course_id' => $course_id
                        ]);
                    }
                    if($user->role_id == 1 || $user->role_id == 2){
                        Progress::create([
                            'user_id' => $userid,
                            'course_id' => $course_id
                        ]);
                    }
                }


            }
            $response = ['message' => "Gli utenti sono stati inseriti con successo"];
            return response()->json($response, 200);
        }

        $response = ['message' => "non è stata trovato il corso"];
        return response()->json($response, 404);
    }
    /**
     * @OA\delete(
     *      path="/api/courses/removeUsersCourse/{course_id}",
     *      summary="Tutor - rimuove un utente dal corso, passandogli l'id dell'utente nel body",
     *      description="remove user from course",
     *      operationId="removeUsersCourse",
     *      tags={"Progress"},
     *      security={{ "apiAuth": {} }},
     *      @OA\Parameter(
     *          description="course id",
     *          in="path",
     *          name="course_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          description="User credentials",
     *          @OA\JsonContent(
     *              required={"user_id"},
     *                  @OA\Property(property="user_id", type="int", format="user_id")
     *              ),
     *      ),
     *
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *      )
     *  )
     */
    function removeUsersCourse(Request $request, $course_id){
        request()->validate([
            'user_id' => 'required'
        ]);

        $user_id = $request['user_id'];
        $progress = Progress::where('user_id', $user_id)->where('course_id', $course_id)->get();
        foreach($progress as $p)
        {
            $p->delete();
        }
        return response("Utente eliminato con successo dal corso");
    }
//prende in ingresso lo user_id ed il course_id,
//se l'utente ha un role_id!=3
//crea una riga
//con lo state=null e lo step_id=null
//altrimenti crea una riga con lo step_id == 1 e lo state = non abilitato

    /**
 * @OA\Post(
 *      path="/api/courses/addCourse",
 *      summary="Tutor - Permette di creare un corso",
 *      description="Add course",
 *      operationId="addCourse",
 *      tags={"Courses"},
 *      security={{ "apiAuth": {} }},
 *      @OA\RequestBody(
 *          description="Course credentials",
 *          @OA\JsonContent(
 *              required={"name","state","description"},
 *                  @OA\Property(property="name", type="string", format="name"),
 *                  @OA\Property(property="state", type="string", format="state"),
 *                  @OA\Property(property="description", type="string", format="description")
 *              ),
 *      ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */


    function addCourse(Request $request){
        request()->validate([
            'name' => 'required',
            'description' => 'required|max:191',
        ]);
        $name = $request['name'];
        $state = config('enums.state.course.1');
        $description = $request['description'];

        Course::create([
            'name' => $name,
            'state' => $state,
            'description'=> $description
        ]);
        return response()->json("Corso creato con successo", 200);
    }

    /**
 * @OA\Put(
 *      path="/api/courses/editCourse/{id}",
 *      summary="Tutor - modifica i dati del corso, si possono modificare nome, stato e descrizione",
 *      description="Edit course",
 *      operationId="editCourse",
 *      tags={"Courses"},
 *      security={{ "apiAuth": {} }},
 *      @OA\Parameter(
 *          description="course Id",
 *          in="path",
 *          name="id",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          description="User credentials",
 *          @OA\JsonContent(
 *              required={"name","state","description"},
 *                  @OA\Property(property="name", type="string", format="name"),
 *                  @OA\Property(property="state", type="string", format="state"),
 *                  @OA\Property(property="description", type="string", format="description")
 *              ),
 *      ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */

    function editCourse(Request $request,$id){

        $course = Course::findOrFail($id);
        $course->fill($request->all())->save();

        return response()->json("Modifiche Salvate", 200);

    }

     /**
    * @OA\delete(
    *   path="/api/courses/deleteCourse/{id}",
    *   summary="Tutor, cancella il corso indicato",
    *   description="delete the course by id",
    *   operationId="deleteCourse",
    *   tags={"Courses"},
    *   security={{ "apiAuth": {} }},
    *   @OA\Parameter(
    *       description="course Id",
    *       in="path",
    *       name="id",
    *       required=true,
    *       @OA\Schema(
    *           type="integer",
    *           format="int64"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function deleteCourse($id){
        $course = Course::findOrFail($id);
        if($course)
        {
            $course->delete();
            DB::delete('delete from progress where course_id = ?', [$id]);
            $response = 'Record eliminato con successo';
            return response($response, 200);
        }
        else {
            return response("Corso non trovato", 404);
        }

    }

}
