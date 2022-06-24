<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware'=>'auth:api'],function (){

//***********USER************

    Route::get('/getAllUsers',[UserController::class , 'index']);
    Route::post('/storeUser',[UserController::class , 'store']);
    Route::get('/showUser/{id}',[UserController::class , 'show']);
    Route::post('/updateUser/{id}',[UserController::class , 'update']);
    Route::delete('/deleteUser/{id}',[UserController::class , 'destroy']);
});


Route::post('/login',[Controller::class , 'login'])->name('login');
Route::post('/register',[Controller::class , 'register'])->name('register');

//***********POST************

Route::get('/getAllPosts',[PostController::class , 'index']);
Route::get('/postSearch',[PostController::class , 'search']);
Route::get('/getUserPosts/{id}',[PostController::class , 'getUserPosts']);
Route::post('/storePost',[PostController::class , 'store']);
Route::get('/showPost/{id}',[PostController::class , 'show']);
Route::post('/updatePost/{id}',[PostController::class , 'update']);
Route::delete('/deletePost/{id}',[PostController::class , 'destroy']);

//***********COMMENTS************

Route::post('/storeComment',[CommentController::class , 'store']);
Route::post('/updateComment/{id}',[CommentController::class , 'update']);
Route::delete('/deleteComment/{id}',[CommentController::class , 'destroy']);

//***********LIKES************

Route::post('/storeLike',[LikeController::class , 'store']);
Route::delete('/deleteLike/{id}',[LikeController::class , 'destroy']);
