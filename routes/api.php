<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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


Route::apiResource('customers',CustomerController::class); 

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/upload',[CustomerController::class,'upload']);
Route::get('/download',[CustomerController::class,'download']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/logout',[AuthController::class,'logout']);
});



//route for task
Route::post('/create-task',[TaskController::class,'create']);
Route::put('/update-task/{taskid}',[TaskController::class,'update']);
Route::get('/get-task/{taskid}',[TaskController::class,'get']);
Route::delete('/delete-task/{taskid}',[TaskController::class,'delete']);
Route::get('/get-task',[TaskController::class,'getall']);

