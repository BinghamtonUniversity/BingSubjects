<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\StudiesController;


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

Route::get('/', [AdminController::class, 'admin']);
Route::get('/participants', [AdminController::class, 'participants']);
Route::get('/studies', [AdminController::class, 'studies']);

Route::group(['prefix' => 'api'], function () {
    // Participant Routes
    Route::get('/participants', [ParticipantsController::class,'get_participants']);
    Route::get('/participants/{participant}',[ParticipantsController::class,'get_participant']);
    Route::post('/participants', [ParticipantsController::class,'create_participant']);
    Route::put('/participants/{participant}', [ParticipantsController::class,'update_participant']);
    Route::delete('/participants/{participant}', [ParticipantsController::class,'delete_participant']);

    // Study Routes
    Route::get('/studies', [StudiesController::class,'get_studies']);
});