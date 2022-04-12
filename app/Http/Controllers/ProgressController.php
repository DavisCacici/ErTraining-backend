<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgressResource;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        /**
 * @OA\Put(
 *      path="/api/setStateProgress/{id}",
 *      summary="editCourse",
 *      description="Insegnate - Cambia lo stato da non abilitato ad abilitato",
 *      operationId="setStateProgress",
 *      tags={"Progress"},
 *      security={{ "apiAuth": {} }},
 *      @OA\Parameter(
 *          description="progress Id",
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
 *              required={"state"},
 *                  @OA\Property(property="state", type="string", format="state"),
 *              ),
 *      ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */

    function setStateProgress(Request $request, $id){
        $state = $request['state'];
        if($state!=config('enums.state.progres.2')){
            return response("Scelta non concessa");
        }
        DB::update("update progress set state = \"$state\" where id = $id");
        if($state=="Finito"){
            $value = DB::table('progress')
            ->select('progress.step_id', 'progress.user_id','progress.course_id')
            ->where('progress.id', '=', $id)->get();
            $value = json_decode($value, true);
            $step_id = $value[0]{'step_id'};
            if($step_id == 4){
                return response("Non ci sono più giochi");
            }
            $step_id = $step_id + 1;
            $user_id = $value[0]{'user_id'};
            $course_id = $value[0]{'course_id'};

            DB::table('progress')
            ->insert([
            'step_id' => $step_id,
            'user_id' => $user_id,
            'course_id'=> $course_id,
            'state'=>config('enums.state.progres.1'),
                ]);

            return response("Stato aggiornato a Finito, creato nuovo stato di progresso");
            }
        return response("Stato aggiornato", 200);
    }

/**
 * @OA\Put(
 *      path="/api/changeStateProgress/{id}",
 *      summary="editCourse",
 *      description="Studente - cambia lo stato da abilitato ad in corso, da in corso a finito e crea il prossimo progress",
 *      operationId="changeStateProgress",
 *      tags={"Progress"},
 *      security={{ "apiAuth": {} }},
 *      @OA\Parameter(
 *          description="progress Id",
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
 *              required={"state"},
 *                  @OA\Property(property="state", type="string", format="state"),
 *              ),
 *      ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */

    function changeStateProgress(Request $request, $id){
        $state = $request['state'];
        if($state!=config('enums.state.progres.3') && $state!=config('enums.state.progres.4')){
            return response("Nono ha funzionato $state");
        }
        DB::update("update progress set state = \"$state\" where id = $id");
        if($state=="Finito"){
            $value = DB::table('progress')
            ->select('progress.step_id', 'progress.user_id','progress.course_id')
            ->where('progress.id', '=', $id)->get();
            $value = json_decode($value, true);
            $step_id = $value[0]{'step_id'};
            if($step_id == 4){
                return response("Non ci sono più giochi");
            }
            $step_id = $step_id + 1;
            $user_id = $value[0]{'user_id'};
            $course_id = $value[0]{'course_id'};

            DB::table('progress')
            ->insert([
            'step_id' => $step_id,
            'user_id' => $user_id,
            'course_id'=> $course_id,
            'state'=>config('enums.state.progres.1'),
                ]);

            return response("Stato aggiornato a Finito, creato nuovo stato di progresso");
            }
        return response("Stato aggiornato", 200);
    }

    public function changeProgress($progress_id)
    {
        $progress = Progress::find($progress_id);
        if($progress)
        {
            $progress->state = config('enums.state.progres.3');
            $progress->save();
            event(new SetState('In Corso'));
            return response('stato cambiato con successo');
        }
        return response('Progresso non trovato', 404);
    }
}
