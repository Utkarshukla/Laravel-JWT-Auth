<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('forget-password', [UserController::class,'forgetPassword']);
//Route::group(['middleware'=>'api',function($routes){

    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    Route::get('/logout',[UserController::class,'logout']);
    Route::get('/profile',[UserController::class,'profile']);
    Route::post('/profile-update',[UserController::class,'updateProfile']);
    Route::get('/send-verify-mail/{email}', [UserController::class,'sendVerifyMail']);
    Route::get('/refresh-token',[UserController::class,'refreshToken']);

//}]);