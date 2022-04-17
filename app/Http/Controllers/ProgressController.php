<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgressResource;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\SetState;

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
}
