<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitMeasureController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // boxes actions
    Route::controller(BoxController::class)->group(function () {
        Route::get('/boxes', 'show');
        Route::post('/boxes/close/{id}', 'close');
        Route::post('/boxes/create', 'create');
        Route::patch('/boxes/update/{id}', 'update');
    });

    // product actions
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'show');
        Route::post('/products/create', 'create');
        Route::delete('/products/delete/{id}', 'destroy');
        Route::patch('/products/update/{id}', 'update');
    });

    // unit_measures actions
    Route::controller(UnitMeasureController::class)->group(function () {
        Route::get('/unit_measures', 'show');
        Route::post('/unit_measures/create', 'create');
        Route::delete('/unit_measures/delete/{id}', 'destroy');
        Route::patch('/unit_measures/update/{id}', 'update');
    });

    // categories actions
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'show');
        Route::post('/categories/create', 'create');
        Route::delete('/categories/delete/{id}', 'destroy');
        Route::patch('/categories/update/{id}', 'update');
    });

    //customer actions
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customers', 'show');
        Route::post('/customers/create', 'create');
        Route::delete('/customers/delete/{id}', 'destroy');
        Route::patch('/customers/update/{id}', 'update');
    });

    // roles actions
    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles', 'show');
        Route::post('/roles/create', 'create');
        Route::delete('/roles/delete/{id}', 'destroy');
        Route::patch('/roles/update/{id}', 'update');
    });

    // attendaces actions
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('/attendances', 'show');
        Route::post('/attendances/create', 'create');
        Route::patch('/attendances/update', 'update');
    });

    //headers actions
    Route::controller(HeaderController::class)->group(function () {
        Route::get('/headers', 'show');
        Route::post('/headers/create', 'create');
        Route::post('/attended/{id}', 'attended');
        Route::post('/anulated/{id}', 'anulated');
    });

    // details actions
    Route::controller(DetailController::class)->group(function () {
        Route::post('/details/create/{id}', 'create');
        Route::get('/details', 'show');
    });

    // user actions
    Route::controller(UserController::class)->group(function () {
        Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/update/{id}', 'update');
    });

    // orders acctions
    Route::post('/orders/create', [OrderController::class, 'create']);

    //actions authentication
    Route::post('/auth/register', [AuthController::class, 'register']);

    // user actions
    Route::get('/users', function () {
        return UserResource::collection(User::all());
    });
});

// user authentication
Route::post('/auth/login', [AuthController::class, 'login']);
