<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\CheckUserController;
use App\Http\Controllers\PasswordResetController;

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

//Sanctum API in Laravel
// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::get('logout', 'logout')->middleware('auth:sanctum');
// });


// public Route
// Route::post('register', [UserController::class, 'register']);
// Route::post('login', [UserController::class, 'login']);
// Route::post('send_reset_password_email', [PasswordResetController::class, 'send_reset_password_email']);
// Route::post('reset_password/{token}', [PasswordResetController::class, 'reset']);

// protected Route
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('logout', [UserController::class, 'logout']);
//     Route::get('logged_user', [UserController::class, 'logged_user']);
//     Route::post('change_password', [UserController::class, 'change_password']);
// });



// Route::post('role_register', [RoleController::class, 'register']);
// Route::post('role_login', [RoleController::class, 'login']);
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('logout', [RoleController::class, 'logout']);
// });

//Resource Controller with sanctum athentication
// Route::post('login', [AuthController::class, 'signin']);
// Route::post('register', [AuthController::class, 'signup']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::resource('blogs', BlogController::class);
// });


//sanctum athentication using middleware
Route::post('register', [CheckUserController::class, 'register']);
Route::post('login', [CheckUserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [CheckUserController::class, 'user'])->middleware('role:user');
    Route::get('admin', [CheckUserController::class, 'admin'])->middleware('role:admin');
    Route::get('provider', [CheckUserController::class, 'provider'])->middleware('role:provider');
});
