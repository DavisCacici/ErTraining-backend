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
class UserController extends Controller
{
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

    function usersList(){
        $usersList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')->get();
        // dd($progres);
        return response()->json($usersList, 200);
    }


    function tutorsList(){
        $tutorsList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')
        ->where('users.role_id', '=', '1')->get();
        // dd($progres);
        return response()->json($tutorsList, 200);
    }

    function teachersList(){
        $teachersList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')
        ->where('users.role_id', '=', '2')->get();
        // dd($progres);
        return response()->json($teachersList, 200);
    }

    function studentsList(){
        $studentsList = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email')
        ->where('users.role_id', '=', '3')->get();
        // dd($progres);
        return response()->json($studentsList, 200);
    }

    function getUser($id){
        $getUser = DB::table('users')
        ->select('users.id', 'users.user_name', 'users.email', 'users.role_id')
        ->where('users.id', '=', $id)->get();
        // dd($progres);
        return response()->json($getUser, 200);
    }

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
