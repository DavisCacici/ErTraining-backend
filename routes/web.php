<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Events\SetState;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/send', [UserController::class, 'store']);

Route::get('/message', function () {
    $message = 'hola';
    $success = event(new SetState($message));
    return $success;
});