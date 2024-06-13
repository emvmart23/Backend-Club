<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnitMeasureController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController;
use App\Http\Resources\UserResource;
use App\Models\User;

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
Route::get('/users', function () {return UserResource::collection(User::all());});
Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::patch('/users/update/{id}', [UserController::class, 'update']);

// product actions
Route::get('/products', [ProductController::class, 'show']);
Route::post('/products/create', [ProductController::class, 'create']);
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy']);
Route::patch('/products/update/{id}', [ProductController::class, 'update']);

// unit_measures actions
Route::get('/unit_measures', [UnitMeasureController::class, 'show']);
Route::post('/unit_measures/create', [UnitMeasureController::class, 'create']);
Route::delete('/unit_measures/delete/{id}', [UnitMeasureController::class, 'destroy']);
Route::patch('/unit_measures/update/{id}', [UnitMeasureController::class, 'update']);

// categories actions
Route::get('/categories', [CategoryController::class, 'show']);
Route::post('/categories/create', [CategoryController::class, 'create']);
Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy']);
Route::patch('/categories/update/{id}', [CategoryController::class, 'update']);

Route::get('/customers', [CustomerController::class, 'show']);
Route::post('/customers/create', [CustomerController::class, 'create']);
Route::delete('/customers/delete/{id}', [CustomerController::class, 'destroy']);
Route::patch('/customers/update/{id}', [CustomerController::class, 'update']);

Route::get('/roles', [RoleController::class, 'show']);
Route::post('/roles/create', [RoleController::class, 'create']);
Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy']);
Route::patch('/roles/update/{id}', [RoleController::class, 'update']);

Route::get('/attendances', [AttendanceController::class, 'show']);
Route::post('/attendances/create', [AttendanceController::class, 'create']);
Route::patch('/attendances/update', [AttendanceController::class, 'update']);

Route::post('/boxes/create', [BoxController::class, 'create']);
Route::patch('/boxes/update/{id}', [BoxController::class, 'update']);

Route::get('/boxes', [BoxController::class, 'show']);

Route::post('/attended/{id}', [HeaderController::class, 'attended']);
Route::post('/orders/create', [OrderController::class, 'create']);

Route::post('/headers/create', [HeaderController::class, 'create']);
Route::get('/headers', [HeaderController::class, 'show']);

Route::post('/boxes/close/{id}', [BoxController::class, 'close']);


Route::post('/details/create', [DetailController::class, 'create']);
Route::get('/details', [DetailController::class, 'show']);

// Route::middleware('auth:api') -> group(function() {
// });