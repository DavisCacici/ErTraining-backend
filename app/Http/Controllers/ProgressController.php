<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgressResource;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\SetState;
use App\Models\User;
use App\Models\Course;

class ProgressController extends Controller
{


     /**
    * @OA\Get(
    *   path="/api/getProgress/{id}",
    *   summary="Insegnate - Ritorna tutti i progress degli utenti che partecipano al corso indicato",
    *   description="Get all the users and their progress of a course",
    *   operationId="getProgress",
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

    function getProgress($id){

        $progress = Progress::where('course_id', $id)
                            ->whereHas('user', function($query){
                                return $query->where('role_id', '=', 3);
                            })
                            ->with('course', 'user')->get();

        return ProgressResource::collection($progress);
    }



    public function studentInProgress($progress_id)
    {
        $progress = Progress::find($progress_id);
        if($progress)
        {
            $progress->state = config('enums.state.progres.3');
            $progress->save();
            // event(new SetState('hola'));
            return response('stato cambiato con successo');
        }
        return response('Progresso non trovato', 404);
    }

    public function teacherSetAble($course_id)
    {
        $progress = Progress::where('course_id', $course_id);
        if($progress){

        }
    }

    /**
    * @OA\Get(
    *   path="/api/getProgressUser/{course_id}",
    *   summary="studente - Ritorna tutti i progressi di un corso di uno studente",
    *   description="Get all the progress of a course of a user",
    *   operationId="getProgressUser",
    *   tags={"Progress"},
    *   security={{ "apiAuth": {} }},
    *   @OA\Parameter(
    *       description="course_id",
    *       in="path",
    *       name="course_id",
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

    public function getProgressUser(Request $request, $course_id){
            $token = $request->bearerToken();
            $tokenParts = explode(".", $token);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);
            $user = User::find($jwtPayload->id);
            $user_id = $user['id'];

            $getProgressUser = DB::table('progress')
            ->select('progress.course_id','progress.step_id', 'progress.state')
            ->where('progress.user_id', '=', $user_id)
            ->where('progress.course_id', '=', $course_id)
            ->get();
            return response()->json($getProgressUser, 200);

    }
}


