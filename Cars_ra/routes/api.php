<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;




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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route for signup
Route::post('/signup', [AuthController::class, 'signup']);

// Route for login
Route::post('login', [AuthController::class, 'login']);

// Route for logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


/////////////////// Admin ///////////////////


Route::middleware(['auth:sanctum', 'is_admin'])->post('/users/{id}/make-admin', [AdminController::class, 'makeAdmin']);


Route::get('/admin/users', [AdminController::class, 'index'])->middleware('auth:sanctum');

Route::put('/admin/users/{id}', [AdminController::class, 'update'])->middleware('auth:sanctum');

Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/admin/users/{id}', [AdminController::class, 'show'])->middleware('auth:sanctum');



/////////////////// Users ///////////////////


//Route show all user

//Route show user by id
Route::get('/users/{id}', [AuthController::class, 'show'])->middleware('auth:sanctum');

//Route update user
Route::put('users/{user_id}', [AuthController::class, 'updateUser'])->middleware('auth:sanctum');

//Route delete user
Route::delete('users/{user_id}', [AuthController::class, 'deleteUser'])->middleware('auth:sanctum');


/////////////////// Cars ///////////////////


//Route show and filter cars
Route::get('/cars', [CarController::class, 'showall']);

Route::get('/cars', [CarController::class, 'index']);

//Route show cars by id
Route::get('/cars/{id}', [CarController::class, 'show']);

//Route add cars
Route::post('/cars', [CarController::class, 'store']);

//Route update cars
Route::put('/cars/{id}', [CarController::class, 'update']);

//Route Delete cars
Route::delete('/cars/{id}', [CarController::class, 'destroy']);


/////////////////// Admin ///////////////////


//Route add bookings
Route::post('/bookings', [BookingController::class, 'create']);

//Route show all bookings
Route::get('/bookings', [BookingController::class, 'index']);

//Route show bookings by id
Route::get('/bookings/{id}', [BookingController::class, 'show']);

//Route Delete bookings
Route::put('/bookings/{id}/cancel', action: [BookingController::class, 'cancel']);


/////////////////// Admin ///////////////////


//Route add payments
Route::post('/payments', [PaymentController::class, 'store']);

//Route show all payments
Route::get('/payments', [PaymentController::class, 'index']);

//Route show payments by id
Route::get('/payments/{id}', [PaymentController::class, 'show']);

//Route update payments
Route::put('/payments/{id}', [PaymentController::class, 'update']);

//Route Delete payments
Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);


/////////////////// Admin ///////////////////


// إضافة تقييم
Route::post('/reviews', [ReviewController::class, 'store']);


Route::get('/reviews/{id}', [ReviewController::class, 'show']);

// عرض جميع التقييمات
Route::get('/reviews', [ReviewController::class, 'index']);

// تعديل تقييم
Route::put('/reviews/{id}', [ReviewController::class, 'update']);

// حذف تقييم
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->middleware('auth:sanctum');





Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
