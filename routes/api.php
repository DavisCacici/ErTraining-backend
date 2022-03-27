<?php

use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Course;
use App\Models\Progres;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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






//in questo gruppo può fare le chiamate solo chi ha il ruole tutor
Route::middleware('tutor')->group(function(){
    Route::post('/register', [UserController::class, 'register'])->name('register.user');
    Route::get('/check', function (){
        return response()->json("ci sono", 200);
    });
   
});

//in questo gruppo può fare le chiamate solo chi ha il ruole teacher
Route::middleware('teacher')->group(function(){
    Route::get('/progres/{id}', function($id){
        $progres = DB::table('progres')
                ->join('steps', 'progres.step_id', '=', 'steps.id')
                ->join('course_user', 'progres.course_user_id', '=', 'course_user.id')
                ->join('courses', 'course_user.course_id', '=', 'courses.id')
                ->join('users', 'course_user.user_id', '=', 'users.id')
                ->select(['steps.step', 'progres.state', 'users.user_name', 'courses.name'])
                ->where('courses.id', '=', $id)
                ->where('users.role_id', '=', 3)->get();
        
        // dd($progres);
        return response()->json($progres, 200);
    });
    Route::get('/courseTeacher', [CourseController::class, 'index']);
});

//in questo gruppo può fare le chiamate solo chi ha il ruole student
Route::middleware('student')->group(function(){
    Route::get('/courseStudent', [CourseController::class, 'index']);
});

//in questo gruppo può fare le chiamate solo chi è autenticato
Route::middleware('checkAuth')->group(function(){
    Route::get('/users', function(){
        $user = User::find(4);
        return $user;
    });
    Route::get('/logout', [UserController::class, 'logout'])->name('logout.user');
});

