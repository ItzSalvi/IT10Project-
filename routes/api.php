<?php

use App\Http\Controllers\Api\BottleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Bottle recycling API routes
// Check if user is logged in
Route::get('/check-login/{userId}', [BottleController::class, 'checkLogin']);

// Handle bottle insertion from Arduino
Route::post('/bottle-inserted', [BottleController::class, 'bottleInserted']);

// Get session status for Arduino connection
Route::get('/session-status', [BottleController::class, 'getSessionStatus']);

