<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\StudiesController;
use App\Http\Controllers\StudyDataController;
use App\Http\Controllers\UsersController;



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
Route::get('/studies/{study}/participants', [AdminController::class, 'study_participants']);
Route::get('/participants/{participant}/studies', [AdminController::class, 'participant_studies']);

Route::group(['prefix' => 'api'], function () {
    // Users Routes
    Route::get('/users/search/{value}', [UsersController::class,'search']);

    // Participant Routes
    Route::get('/participants', [ParticipantsController::class,'get_participants']);
    Route::get('/participants/{participant}',[ParticipantsController::class,'get_participant']);
    Route::post('/participants', [ParticipantsController::class,'create_participant']);
    Route::put('/participants/{participant}', [ParticipantsController::class,'update_participant']);
    Route::delete('/participants/{participant}', [ParticipantsController::class,'delete_participant']);


    // Study Routes
    Route::get('/studies', [StudiesController::class,'get_studies']);
    Route::get('/studies/{study}',[StudiesController::class,'get_study']);
    Route::post('/studies', [StudiesController::class,'create_study']);
    Route::put('/studies/{study}', [StudiesController::class,'update_study']);
    Route::delete('/studies/{study}', [StudiesController::class,'delete_study']);

    // Study Participant Routes
    Route::get('/studies/{study}/participants', [StudiesController::class,'get_study_participant']);
    Route::get('/participants/{participant}/studies', [ParticipantsController::class, 'get_participant_studies']);

    Route::post('/studies/{study}/participants/{participant}', [StudiesController::class,'add_study_participant']);
    Route::delete('/studies/{study}/participants/{participant}', [StudiesController::class,'delete_study_participant']);

    Route::post('/participants/{participant}/studies/{study}', [ParticipantsController::class,'add_participant_study']);
    Route::delete('/participants/{participant}/studies/{study}', [ParticipantsController::class,'delete_participant_study']);

    // Study Data Routes
    // Get all study data entries
    Route::get('/study-data', [StudyDataController::class,'get_study_data']);
    
    // // Get study data from study - Should this take place here or from the StudyController?
    // Route::get('/study-data/{study}',[StudyDataController::class,'get_studys_data']);
    
    // Post study data directly to study
    Route::post('/study-data/{study}', [StudyDataController::class,'create_study_data']);   

    // // Determine if this request needs to come directly from a study
    // Route::put('/study-data/{study-data}', [StudyDataController::class,'update_study_data']);
    // Route::delete('/study-data/{study-data}', [StudyDataController::class,'delete_study_data']);
});
