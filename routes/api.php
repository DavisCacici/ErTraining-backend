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

Route::middleware('tutor')->group(function(){

    //users
    Route::prefix('users')->group(function(){
        /**Ritorna l'intera lista degli utenti presenti a database */
        Route::get('/usersList', [UserController::class, 'usersList']);
        /**Ritorna la lista dei tutor presenti a database */
        Route::get('/tutorsList', [UserController::class, 'tutorsList']);
        /**Ritorna la lista dei teacher presenti a database */
        Route::get('/teachersList', [UserController::class, 'teachersList']);
        /**Ritorna la lista dei student presenti a database */
        Route::get('/studentsList', [UserController::class, 'studentsList']);
        /**Ritorna i dettagli di un utente specifico passando semplicemente il suo ID */
        Route::get('/getUser/{id}', [UserController::class, 'getUser']);
        /**Chiamata che serve per creare un nuovo utente passare al suo interno user_name, password, email e role_id */
        Route::post('/addUser', [UserController::class, 'addUser']);
        /**Chiamata che serve per modificare un nuovo utente passando l'id dell'utente al suo interno user_name, password, email e role_id */
        Route::put('/editUser/{id}', [UserController::class, 'editUser']);
        Route::put('/editPassword/{id}', [UserController::class, 'editPassword']);
        /**Chiamata per eliminare un qualsiasi id basta passare l'id*/
        Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
        Route::get('/courses/{user_id}', [CourseController::class, 'getCourseUser']);
    });

    //courses
    Route::prefix('courses')->group(function(){
        Route::get('/coursesList', [CourseController::class, 'coursesList']);
        Route::get('/getCourse/{id}', [CourseController::class, 'getCourse']);
        Route::post('/addCourse', [CourseController::class, 'addCourse']);
        Route::put('editCourse/{id}', [CourseController::class, 'editCourse']);
        Route::delete('/deleteCourse/{id}', [CourseController::class, 'deleteCourse']);
        Route::get('/getUsersCourse/{id}', [CourseController::class, 'getUsersCourse']);
        Route::put('/addUsersCourse/{course_id}', [CourseController::class, 'addUsersCourse']);
        Route::delete('/removeUsersCourse/{course_id}', [CourseController::class, 'removeUsersCourse']);
    });

});

/**
 * Chiamate per il teacher:
 *
 * vedere i progress di tutti gli studenti di quel corso
 * abilitare step nella tabella progress
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole teacher
Route::middleware('teacher')->group(function(){
    Route::get('/courseTeacher', [CourseController::class, 'index']);
    Route::get('/getProgress/{id}', [ProgressController::class, 'getProgress']);
    
});


/**
 * settare un progres come in corso
 * settare un progres come finito
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole student
Route::middleware('student')->group(function(){

    Route::get('/courseStudent', [CourseController::class, 'index']);
    Route::get('/studentInProgress', [CourseController::class, 'studentInProgress']);
   
});


//in questo gruppo può fare le chiamate solo chi è autenticato
Route::middleware('api')->group(function(){
    Route::post('/logout', [UserController::class, 'logout'])->name('logout.user');
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/resetPassword', [UserController::class, 'resetPassword']);
    Route::post('/changeData', [UserController::class, 'changeData']);
    Route::get('/getUserCourses', [CourseController::class, 'getUserCourses']);
});

Route::get('/changeProgress/{progress_id}', [ProgressController::class, 'changeProgress']);
