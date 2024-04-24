<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
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
Route::get('/products', [ProductController::class, 'show']);
Route::post('/products/create', [ProductController::class, 'create']);
Route::delete('/products/delete/{id}', [ProductController::class, 'delete']);
Route::patch('/products/update/{id}', [ProductController::class, 'update']);

// unit_measures actions
Route::get('/unit_measures, {id}', [ProductController::class, 'show']);
Route::post('/unit_measures/create', [ProductController::class, 'create']);
Route::delete('/unit_measures/delete/{id}', [ProductController::class, 'delete']);
Route::patch('/unit_measures/update/{id}', [ProductController::class, 'update']);

// categories actions
Route::get('/categories, {id}', [ProductController::class, 'show']);
Route::post('/categories/create', [ProductController::class, 'create']);
Route::delete('/categories/delete/{id}', [ProductController::class, 'delete']);
Route::patch('/categories/update/{id}', [ProductController::class, 'update']);


