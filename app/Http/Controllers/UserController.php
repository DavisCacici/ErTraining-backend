<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function login(Request $request)
    {
        // $credentials = request(['email', 'password']);
        $user = User::where('email',$request['email'])->with('role')->first();
        // if (! $token = auth()->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        if($user)
        {
            // dd($request['password']);
            if(Hash::check($request->password, $user->password))
            {
                $token = auth()->claims(['user_id'=> $user->id,'user_name' => $user->user_name, 'role' => $user->role_id])->login($user);
                return $this->respondWithToken($token);
            }else{
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // $validator = Validator::make($request->all(),[
        //     'email' => 'required|string',
        //     'password' => 'required|min:6',
        // ]);
        // if($validator->fails())
        // {
        //     return response($validator->errors()->all(), 500);
        // }
        // $user = User::where('email',$request['email'])->with('role')->first();
        // if($user)
        // {
        //     // dd($request['password']);
        //     if(Hash::check($request->password, $user->password))
        //     {
        //         $user->createToken();
        //         $result = ['token'=>$user->token, 'role'=>$user->role];
        //         return response($result, 200);
        //     }
        //     else
        //     {
        //         return response('password errata', 422);
        //     }
        // }
        // else
        // {
        //     return response('Email errata', 422);
        // }

    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
        // $token = $request->bearerToken();
        // $user = User::where('token', $token)->first();

        // $user->revoke();
        // // dd($user);
        // $responseMessage = "successfully logged out";
        // return response()->json($responseMessage ,200);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
//     public function register(Request $request){
//         $validator = Validator::make($request->all(),[
//             'name' => 'required|string',
//             'email' => 'required|string|unique:users',
//             'password' => 'required|min:6|confirmed',
//         ]);
//         if($validator->fails()){
//            return response(['errors'=>$validator->errors()->all()], 422);
//         }
//         $data = [
//             "name" => $request->name,
//             "email" => $request->email,
//             "password" => Hash::make($request->password)
//         ];
//         $this->user->create($data);
//         $responseMessage = "Registration Successful";
//         return response()->json([
//             'success' => true,
//             'message' => $responseMessage
//         ], 200);
//     }




// public function login(Request $request){
// $validator = Validator::make($request->all(),[
// 'email' => 'required|string',
// 'password' => 'required|min:6',
// ]);
// if($validator->fails()){
// return response()->json([
// 'success' => false,
// 'message' => $validator->messages()->toArray()
// ], 500);
// }
// $credentials = $request->only(["email","password"]);
// $user = User::where('email',$credentials['email'])->first();
// if($user){
// if(!auth()->attempt($credentials)){
// $responseMessage = "Invalid username or password";
// return response()->json([
// "success" => false,
// "message" => $responseMessage,
// "error" => $responseMessage
// ], 422);
// }
// $accessToken = auth()->user()->createToken('authToken')->plainTextToken;
// $responseMessage = "Login Successful";
// return $this->respondWithToken($accessToken,$responseMessage,auth()->user());
// }
// else{
// $responseMessage = "Sorry, this user does not exist";
// return response()->json([
// "success" => false,
// "message" => $responseMessage,
// "error" => $responseMessage
// ], 422);
// }
// }
// public function viewProfile(){
// $responseMessage = "user profile";
// $data = Auth::guard("api")->user();
// return response()->json([
// "success" => true,
// "message" => $responseMessage,
// "data" => $data
// ], 200);
// }
// public function logout(){
// $user = Auth::guard("api")->user()->token();
// $user->revoke();
// $responseMessage = "successfully logged out";
// return response()->json([
// 'success' => true,
// 'message' => $responseMessage
// ], 200);
// }
}
