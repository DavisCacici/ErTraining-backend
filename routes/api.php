<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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


Route::post('/login', [UserController::class, 'login'])->name('login.user');



Route::middleware('tutor')->group(function(){
    Route::post('/register', [UserController::class, 'register'])->name('register.user');
});

Route::middleware('teacher')->group(function(){

});

Route::middleware('student')->group(function(){

});

Route::middleware('checkAuth')->group(function(){
    // Route::get('/users', function(){
    //     $user = User::find(4);
    //     return $user;
    // });
    Route::get('/logout', [UserController::class, 'logout'])->name('logout.user');
});
