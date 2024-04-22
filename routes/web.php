<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Http\Resources\UserResource;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// user authentication
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// user actions
Route::get('/users ', function () { return UserResource::collection(User::all()); });
Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::patch('/users/update/{id}', [UserController::class, 'update']);

// product actions