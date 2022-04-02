<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProgressController;
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
Route::post('/recovery', [UserController::class, 'recovery']);

/**
 * chiamate per tutor:
 * liste teacher, student, tutor (get)
 * show teacher, student, tutor (get con id nel url /{id})
 * edit teacher, student, tutor tranne se stesso (put con id nel url /{id})
 * delete teacher, student, tutor tranne se stesso (delete con id nel url /{id})
 * create teacher, student, tutor (post)(quando viene creato inviare una email)
 * 
 * lista corsi (tutti i corsi)
 * show course (vedere dati corso)
 * show user in that course (passando l'id del corso)
 * create course ?
 * delete course
 * edit course (editare dato corso)
 * edit user in that course (editare i partecipanti a quel corso)
 * 
 * vedere i progres di tutti gli studenti di quel corso
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole tutor
Route::middleware('tutor')->group(function(){
    // Route::post('/register', [UserController::class, 'register'])->name('register.user');
    // Route::post('/courseCreate', [CourseController::class, 'create']);

});

/**
 * vedere i progres di tutti gli studenti di quel corso
 * abilitare step nella tabella progres
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole teacher
Route::middleware('teacher')->group(function(){
    Route::get('/progres/{id}', [ProgressController::class, 'index']);
    Route::get('/courseTeacher', [CourseController::class, 'index']);
});


/**
 * settare un progres come in corso
 * settare un progres come finito
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole student
Route::middleware('student')->group(function(){
    Route::get('/courseStudent', [CourseController::class, 'index']);
});


//in questo gruppo può fare le chiamate solo chi è autenticato
Route::middleware('api')->group(function(){
    Route::post('/logout', [UserController::class, 'logout'])->name('logout.user');
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/resetPassword', [UserController::class, 'resetPassword']);
    Route::post('/changeData', [UserController::class, 'changeData']);
});

