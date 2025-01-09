<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\ZoomController; // Add this line
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

Route::get('/', [MainController::class, 'index']);
Route::get('/meeting', [MeetingController::class, 'index']);
Route::post('/generate-signature', [SignatureController::class, 'generate']);
Route::post('/schedule-meeting', [ZoomController::class, 'scheduleMeeting']); // Add this line
Route::get('/meetings', [ZoomController::class, 'getMeetings']); // Add this line
Route::get('/join-meeting/{meeting_id}', [ZoomController::class, 'joinMeeting']); // Add this line