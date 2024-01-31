<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\StudiesController;
use App\Http\Controllers\DataTypesController;
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
Route::get('/users/{user?}', [AdminController::class, 'users']);
Route::get('/participants', [AdminController::class, 'participants']);
Route::get('/studies', [AdminController::class, 'studies']);
Route::get('/studies/{study}/participants', [AdminController::class, 'study_participants']);
Route::get('/participants/{participant}/studies', [AdminController::class, 'participant_studies']);
Route::get('/data_types', [AdminController::class,'data_types']);
Route::get('/studies/{study}/data_types', [AdminController::class,'study_data_types']);
Route::get('/data_types/{data_type}/studies', [AdminController::class,'data_type_studies']);

Route::group(['prefix' => 'api'], function () {
    // User Routes
    Route::get('/users/search/{value}', [UsersController::class,'search']);

    Route::get('/users', [UsersController::class,'get_users']);//->middleware('can:view_users,App\Models\User');
    Route::get('/users/{user}', [UsersController::class,'get_user']);//->middleware('can:view_users,App\Models\User');
    Route::post('/users', [UsersController::class,'create_user']);//->middleware('can:manage_users,App\Models\User');
    Route::put('/users/{user}', [UsersController::class,'update_user']);//->middleware('can:manage_users,App\Models\User');
    Route::delete('/users/{user}', [UsersController::class,'delete_user']);//->middleware('can:manage_users,App\Models\User');
    // User Permissions
    Route::put('/users/{user}/permissions',[UsersController::class,'set_permissions']);//->middleware('can:manage_permissions,App\Models\User');
    Route::get('/users/{user}/permissions',[UsersController::class,'get_permissions']);//->middleware('can:view_permissions,App\Models\User');

    Route::get('/users', [UsersController::class,'get_users']);
    Route::get('/users/{user}', [UsersController::class,'get_user']);
    Route::post('/users', [UsersController::class,'create_user']);
    Route::put('/users/{user}', [UsersController::class,'update_user']);
    Route::put('/users/{user}/permissions', [UsersController::class,'set_permissions']);
    Route::delete('/users/{user}', [UsersController::class,'delete_user']);


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
    // Study Permissions
    Route::put('/studies/{study}/users/{user}/permissions',[StudiesController::class,'set_study_permissions']);
    Route::get('/studies/{study}/users/{user}/dpermissions',[StudiesController::class,'get_study_permissions']);

    // Study Participant Routes
    Route::get('/studies/{study}/participants', [StudiesController::class,'get_study_participant']);
    Route::get('/participants/{participant}/studies', [ParticipantsController::class, 'get_participant_studies']);

    Route::post('/studies/{study}/participants/{participant}', [StudiesController::class,'add_study_participant']);
    Route::delete('/studies/{study}/participants/{participant}', [StudiesController::class,'delete_study_participant']);

    Route::post('/participants/{participant}/studies/{study}', [ParticipantsController::class,'add_participant_study']);
    Route::delete('/participants/{participant}/studies/{study}', [ParticipantsController::class,'delete_participant_study']);

    // Data Type Routes
    Route::get('/data_types', [DataTypesController::class,'get_data_types']);
    Route::get('/data_types/{data_type}',[DataTypesController::class,'get_data_type']); // not found
    Route::post('/data_types', [DataTypesController::class,'create_data_type']);
    Route::put('/data_types/{data_type}', [DataTypesController::class,'update_data_type']); // not found
    Route::delete('/data_types/{data_type}', [DataTypesController::class,'delete_data_type']); // not found

    // Study Data Type Routes
    Route::get('/studies/{study}/data_types', [StudiesController::class,'get_study_data_type']);
    Route::get('/data_types/{data_type}/studies', [DataTypesController::class, 'get_data_type_studies']);

    Route::post('/studies/{study}/data_types/{data_type}', [StudiesController::class,'add_study_data_type']);
    Route::delete('/studies/{study}/data_types/{data_type}', [StudiesController::class,'delete_study_data_type']);

    Route::post('/data_types/{data_type}/studies/{study}', [DataTypesController::class,'add_data_type_study']);
    Route::delete('/data_types/{data_type}/studies/{study}', [DataTypesController::class,'delete_data_type_study']);
});
