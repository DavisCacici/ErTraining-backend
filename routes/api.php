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

 * liste teacher, student, tutor (get) (fatto)
 * show teacher, student, tutor (get con id nel url /{id}) (fatto)
 * delete teacher, student, tutor tranne se stesso (delete con id nel url /{id} ) (fatto)
 * create teacher, student, tutor (post)(quando viene creato inviare una email) (fatto, compresa l'email)
 *
 * lista corsi (tutti i corsi)
 * show user in that course (passando l'id del corso)  (fatto)
 * delete course    (fatto)
 * create course  (fatto)
 *
 * Da fare
 * show course (vedere dati corso) ??? Quali tipi di dato? --Da fare
 * edit teacher, student, tutor tranne se stesso (put con id nel url /{id})
 * edit course (editare dato corso) -- Da fare
 * edit user in that course (editare i partecipanti a quel corso) --Da fare
 * Add users in a course -- Da fare
 *
 * vedere i progress di tutti gli studenti di quel corso
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole tutor
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
        Route::put('editUser/{id}', [UserController::class, 'editUser']);
        /**Chiamata per eliminare */
        Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
    });
    Route::prefix('courses')->group(function(){
        Route::get('/coursesList', [CourseController::class, 'coursesList']);
        Route::get('/getCourse/{id}', [CourseController::class, 'getCourse']);
        Route::post('/addCourse', [CourseController::class, 'addCourse']);
        Route::put('editCourse/{id}', [CourseController::class, 'editCourse']);
        Route::delete('/deleteCourse/{id}', [CourseController::class, 'deleteCourse']);
        Route::get('/getUsersCourse/{id}', [CourseController::class, 'getUsersCourse']);
        Route::match(['get', 'post'],'/addUsersCourse/{course_id}', [CourseController::class, 'addUsersCourse']);
        Route::delete('/removeUsersCourse/{course_id}', [CourseController::class, 'removeUsersCourse']);
    });

    //courses

});


//Fai una prova a settare la tabella progress come tabella centrale e togliere course_user e mi fai sapere per
//le altre chiamate lasciale in sospeso finchè non sistemiamo il DB
//Invece per l'update del corso e dell'utente ci penso io, e per aggiungere utenti al corso aspettiamo sempre il db
//grazie per l'attenzione ed happy coding :-)


/**
 * Chiamate per il teacher:
 *
 * vedere i progress di tutti gli studenti di quel corso
 * abilitare step nella tabella progress
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole teacher
Route::middleware('teacher')->group(function(){
    //Route::get('/progress/{id}', [ProgressController::class, 'index']);
    Route::get('/courseTeacher', [CourseController::class, 'index']);
    Route::get('/getProgress/{id}', [ProgressController::class, 'getProgress']);
    Route::match(['put', 'get', 'post'],'/setStateProgress/{id}', [ProgressController::class, 'setStateProgress']);
});


/**
 * settare un progres come in corso
 * settare un progres come finito
 */
//in questo gruppo può fare le chiamate solo chi ha il ruole student
Route::middleware('student')->group(function(){
    Route::get('/courseStudent', [CourseController::class, 'index']);
    Route::match(['put', 'get', 'post'],'/changeStateProgress/{id}', [ProgressController::class, 'changeStateProgress']);
});


//in questo gruppo può fare le chiamate solo chi è autenticato
Route::middleware('api')->group(function(){
    Route::post('/logout', [UserController::class, 'logout'])->name('logout.user');
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/resetPassword', [UserController::class, 'resetPassword']);
    Route::post('/changeData', [UserController::class, 'changeData']);
});

