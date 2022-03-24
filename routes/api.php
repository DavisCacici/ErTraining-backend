<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Course;
use App\Models\Progres;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//richiamando login return il token la chiamata la può fare chiunque
Route::post('/login', [UserController::class, 'login'])->name('login.user');

Route::get('/progres', function(){
    $progres = Progres::find(1);
    // dd($progres->courses);
    return response()->json($progres->courseuser(), 200);
});

Route::get('/courseuser', function(){
    $user = User::with('courses')->find(1);
    // dd($progres->courses);
    return response()->json($user, 200);
});


//in questo gruppo può fare le chiamate solo chi ha il ruole tutor
Route::middleware('tutor')->group(function(){
    Route::post('/register', [UserController::class, 'register'])->name('register.user');
    Route::get('/check', function (){
        return response()->json("ci sono", 200);
    });
});

//in questo gruppo può fare le chiamate solo chi ha il ruole teacher
Route::middleware('teacher')->group(function(){

});

//in questo gruppo può fare le chiamate solo chi ha il ruole student
Route::middleware('student')->group(function(){

});

//in questo gruppo può fare le chiamate solo chi è autenticato
Route::middleware('checkAuth')->group(function(){
    Route::get('/users', function(){
        $user = User::find(4);
        return $user;
    });
    Route::get('/logout', [UserController::class, 'logout'])->name('logout.user');
});
