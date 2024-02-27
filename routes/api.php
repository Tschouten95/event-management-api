<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\CategoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(["prefix" => "event"], function() {
    Route::GET("",[EventController::class, "index"]);
    Route::GET("/{id}",[EventController::class, "show"]);
    Route::POST("", [EventController::class, 'store']);
    Route::PUT("/{id}", [EventController::class, 'update']);
    Route::DELETE("/{id}", [EventController::class, 'destroy']);
});

Route::group(["prefix" => "venue"], function() {
    Route::GET("",[VenueController::class, "index"]);
    Route::GET("/{id}",[VenueController::class, "show"]);
    Route::POST("", [VenueController::class, 'store']);
    Route::PUT("/{id}", [VenueController::class, 'update']);
    Route::DELETE("/{id}", [VenueController::class, 'destroy']);
});

Route::group(["prefix" => "category"], function() {
    Route::GET("",[CategoryController::class, "index"]);
    Route::GET("/{id}",[CategoryController::class, "show"]);
    Route::POST("", [CategoryController::class, 'store']);
    Route::PUT("/{id}", [CategoryController::class, 'update']);
    Route::DELETE("/{id}", [CategoryController::class, 'destroy']);
});