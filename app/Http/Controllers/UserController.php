<?php

namespace App\Http\Controllers;

use App\Mail\Recovery;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email',$request['email'])->with('role')->first();
        if($user)
        {
            if(Hash::check($request->password, $user->password))
            {
                $token = auth()->claims(['id'=> $user->id,
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

    // public function store()
    // {
    //     Mail::to('davis.cacici.studio@fitstic-edu.com')->cc('alexandro.burgagni.studio@fitstic-edu.com')->send(new WelcomeMail());
    //     return new WelcomeMail();
    //     // $data = ['message'=>'this is a test'];
    //     // Mail::to('melania.tizzi.studio@fitstic-edu.it')->send(new TestEmail($data));
    // }

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
        $user = User::find($jwtPayload->user_id);
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
        $user = User::find($jwtPayload->user_id);
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
        $user = User::find($jwtPayload->user_id);
        if($user)
        {
            $user->email = $request['email'];
            $user->name = $request['name'];
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

}
