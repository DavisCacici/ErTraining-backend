<?php

namespace App\Http\Controllers;

use App\Mail\Recovery;
use App\Mail\Register;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Progress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 */

class UserController extends Controller
{
 /**
 * @OA\Post(
 * path="/api/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="login",
 * tags={"Auth"},
 * @OA\RequestBody(
 *    description="User credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="leonardo.garuti.studio@fitstic-edu.com"),
 *       @OA\Property(property="password", type="string", format="password", example="password")
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      @OA\JsonContent(
*        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
*     )
 *     )
 * )
 */
    public function login(Request $request)
    {
        $user = User::where('email',$request['email'])->with('role')->first();
        if($user)
        {
            if(Hash::check($request->password, $user->password))
            {
                $token = auth()->claims([
                'id'=> $user->id,
                'name' => $user->user_name,
                'email' => $user->email,
                'role' => $user->role->name])->login($user);
                return $this->respondWithToken($token);
            }else{
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    }
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="LOGS OUT CURRENT LOGGED IN USER SESSION",
     *     operationId="logout",
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     *
     * Logs out current logged in user session.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function recovery(Request $request)
    {

        $user = User::where('email',$request['email'])->first();
        if($user)
        {
            $newPassword = Str::random(16);
            $user->password = Hash::make($newPassword);
            $user->save();
            Mail::to($user->email)->send(new Recovery($newPassword));
        }

        $response = ['message' => 'ti è stata inviata una email con la nuova password'];
        return response()->json($response, 200);

    }

    public function profile(Request $request)
    {
        $token = $request->bearerToken();
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $user = User::find($jwtPayload->id);
        if($user){
            return response()->json($user, 200);
        }
        $response = ['message'=>'token non valido'];
        return response()->json($response, 201);
    }

    public function resetPassword(Request $request)
    {
        $token = $request->bearerToken();
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $user = User::find($jwtPayload->id);
        if($user){
            $user->password = Hash::make($request['password']);
            $user->save();
            $response = ['message'=>'password cambiato con successo'];
            return response()->json($response, 200);
        }

        $response = ['message'=>'token non valido'];
        return response()->json($response, 201);
    }

    public function changeData(Request $request)
    {
        $token = $request->bearerToken();
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $user = User::find($jwtPayload->id);
        if($user)
        {
            $user->email = $request['email'];
            $user->name = $request['user_name'];
            $user->save();
            $response = ['message'=>'dati cambiati con successo', 'user'=>$user];
            return response()->json($response, 200);
        }
        $response = ['message'=>'token non valido'];
        return response()->json($response, 201);

    }

    public function register(Request $request)
    {
        $file = $request->file('file');
        if($file)
        {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $location = 'uploads';
            $file->move($location, $filename);
            $filepath = public_path($location . "/" . $filename);
            file($filepath);
        }
    }
    /**
    * @OA\Get(
    *   path="/api/users/usersList",
    *   summary="Tuttor - Ritorna la lista di tutti gli utenti",
    *   description="Get the list of all users",
    *   security={{ "apiAuth": {} }},
    *   operationId="usersList",
    *   tags={"Users"},
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function usersList(){
        $usersList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')->get();
        // dd($progres);
        return response()->json($usersList, 200);
    }

    /**
    * @OA\Get(
    *   path="/api/users/tutorsList",
    *   summary="Tutor - ritorna la lista di tutti i tutor",
    *   description="Get the list of tutors",
    *   security={{ "apiAuth": {} }},
    *   operationId="tutorsList",
    *   tags={"Users"},
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function tutorsList(){
        $tutorsList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')
        ->where('users.role_id', '=', '1')->get();
        // dd($progres);
        return response()->json($tutorsList, 200);
    }

    /**
    * @OA\Get(
    *   path="/api/users/teachersList",
    *   summary="Tutor, ritorna la lista di tutti gli insegnanti",
    *   description="Get the list of teachers",
    *   security={{ "apiAuth": {} }},
    *   operationId="teachersList",
    *   tags={"Users"},
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */
    function teachersList(){
        $teachersList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')
        ->where('users.role_id', '=', '2')->get();
        // dd($progres);
        return response()->json($teachersList, 200);
    }

    /**
    * @OA\Get(
    *   path="/api/users/studentsList",
    *   summary="Ritorna la lista di tutti gli studenti",
    *   description="Get the list of students",
    *   security={{ "apiAuth": {} }},
    *   operationId="studentsList",
    *   tags={"Users"},
    *   @OA\Response(
    *       response=200,
    *       description="Success"
    *   )
    *)
    */

    function studentsList(){
        $studentsList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')
        ->where('users.role_id', '=', '3')->get();
        // dd($progres);
        return response()->json($studentsList, 200);
    }

    /**
    * @OA\Get(
    *   path="/api/users/getUser/{id}",
    *   summary="Tutor - ritorna le informazioni di un utente",
    *   description="Get the user by id",
    *   operationId="getUser",
    *   tags={"Users"},
    *   security={{ "apiAuth": {} }},
    *   @OA\Parameter(
    *       description="user Id",
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

    function getUser($id){
        $getUser = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email', 'users.role_id')
        ->where('users.id', '=', $id)->get();
        // dd($progres);
        return response()->json($getUser, 200);
    }

/**
 * @OA\Post(
 *      path="/api/users/addUser",
 *      summary="Tutor - crea un nuovo utente",
 *      description="Add user",
 *      operationId="addUser",
 *      tags={"Users"},
 *      security={{ "apiAuth": {} }},
 *      @OA\RequestBody(
 *          description="User credentials",
 *          @OA\JsonContent(
 *              required={"user_name","email","password","role_id"},
 *                  @OA\Property(property="user_name", type="string", format="user_name"),
 *                  @OA\Property(property="email", type="string", format="email"),
 *                  @OA\Property(property="password", type="string", format="password"),
 *                  @OA\Property(property="role_id", type="int", format="role_id")
 *              ),
 *      ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */

    function addUser(Request $request){
        $user_name = $request['user_name'];
        $email = $request['email'];
        $password = $request['password'];
        $role_id = $request['role_id'];
        User::create([
            'user_name' => $user_name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id'=> $role_id
        ]);
        Mail::to($email)->send(new Register($email, $password));

        $response = ['message' => 'ti è stata inviata una email con le credenziali'];
        return response()->json($response, 200);
    }

/**
 * @OA\Put(
 *      path="/api/users/editUser/{id}",
 *      summary="Tutor - modifica le informazioni di un utente",
 *      description="Edit user",
 *      operationId="editUser",
 *      tags={"Users"},
 *      security={{ "apiAuth": {} }},
 *      @OA\Parameter(
 *          description="user Id",
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
 *              required={"user_name","email","password","role_id"},
 *                  @OA\Property(property="user_name", type="string", format="user_name"),
 *                  @OA\Property(property="email", type="string", format="email")
 *              ),
 *      ),
 *
 * @OA\Response(
 *    response=200,
 *    description="OK",
 *      )
 *  )
 */

    function editUser(Request $request, $id){
        $changeValue = false;
        $user = User::find($id);
        $user_name = $request['user_name'];
        $email = $request['email'];
        if($user_name != null){
            $user->user_name = $user_name;
            $changeValue = true;
        }
        if($email != null){
            $user->email = $email;
            $changeValue = true;
        }

        $user->save();
        if($changeValue == true){
            return response("I valori sono stati cambiati correttamente");
        }
        return response("Nessun valore è stato cambiato");

    }


     /**
    * @OA\delete(
    *   path="/api/users/deleteUser/{id}",
    *   summary="Tutor - Cancella un'utente",
    *   description="delete the user by id",
    *   operationId="deleteUser",
    *   tags={"Users"},
    *   security={{ "apiAuth": {} }},
    *   @OA\Parameter(
    *       description="user Id",
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

    function deleteUser($id){

        $user = User::find($id);
        if ($user){
            $user->delete();
            $progress = Progress::where('user_id',$id)->get();
            $progress->delete();
            // DB::delete('delete from progress where user_id = ?', [$id]);
            return response("Record deleted successfully");
        }
        return response("Utente non presente", 404);
    }

}
