<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserCourseRequest;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\EditCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Progress;
use GuzzleHttp\Promise\Create;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $id = $jwtPayload->id;

        $course = Course::whereHas('users', function($query) use ($id){
            $query->where('users.id', $id);
        })->get();
        
        return CourseResource::collection($course);
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

    function coursesList(Request $request){
       
        $coursesList = Course::all();
        return CourseResource::collection($coursesList);
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

        if($course){
            return new CourseResource($course);
        }
        return response("corso non trovato",404);
    }

     /**
    * @OA\Get(
    *   path="/api/courses/getUsersCourse/{id}",
    *   summary="Tutor - Ritorna tutte le informazioni degli utenti iscritti ad un corso",
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

        $users = Course::find($id)->users->where('role_id', '!=', 1);
        // $progress = Progress::where('course_id', $id)->with('step')->leftjoin('users', 'progress.user_id','=', 'users.id')->get();
        // return $progress;
        return UserResource::collection($users);

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

    function addUsersCourse(AddUserCourseRequest $request, $course_id){
        $course = Course::find($course_id);
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
                }
                if($user->role_id == 1 || $user->role_id == 2){
                    Progress::create([
                        'user_id' => $userid,
                        'course_id' => $course_id
                    ]);
                }

            }
            $response = ['message' => "gli utenti sono stati inseriti con successo"];
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
            $user_id = $request['user_id'];
            DB::delete("delete from progress where user_id = $user_id and course_id = $course_id");
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


    function addCourse(CreateCourseRequest $request){
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

    function editCourse(EditCourseRequest $request,$id){
        $course = Course::find($id);
        $course->update($request->all());
        return response("I valori sono stati cambiati correttamente");
    }

     /**
    * @OA\delete(
    *   path="/api/courses/deleteCourse/{id}",
    *   summary="Tutor - cancella il corso indicato",
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

        $course = Course::find($id);

        if($course){
            $course->delete();
            DB::delete('delete from progress where course_id = ?', [$id]);

            return response("Record deleted successfully");
        }
        return response("Corso non trovato", 404);
    }

}
