<?php

use App\Http\Controllers\ReportController;
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

Route::get('/',[AdminController::class,'admin']);
Route::get('/users/{user?}',[AdminController::class,'users']);
Route::get('/participants',[AdminController::class,'participants']);
Route::get('/studies',[AdminController::class,'studies']);
Route::get('/studies/{study}',[AdminController::class,'study']);
Route::get('/studies/{study}/participants',[AdminController::class,'study_participants']);
Route::get('/participants/{participant}/studies',[AdminController::class,'participant_studies']);
Route::get('/data_types',[AdminController::class,'data_types']);
Route::get('/studies/{study}/data_types',[AdminController::class,'study_data_types']);
Route::get('/data_types/{data_type}/studies',[AdminController::class,'data_type_studies']);
Route::get('/reports',[AdminController::class,'reports']);
Route::get('/reports/{report}/run',[AdminController::class,'run_report']);

Route::group(['prefix' => 'api'], function () {
    // User Routes
    Route::get('/users/search/{value}',[UsersController::class,'search']);

    Route::get('/users',[UsersController::class,'get_users'])->middleware('can:view_users,App\Models\User');
    Route::get('/users/{user}',[UsersController::class,'get_user']);   // No view implemented
    Route::post('/users',[UsersController::class,'create_user'])->middleware('can:manage_users,App\Models\User');
    Route::put('/users/{user}',[UsersController::class,'update_user'])->middleware('can:manage_users,App\Models\User');
    Route::delete('/users/{user}',[UsersController::class,'delete_user'])->middleware('can:manage_users,App\Models\User');
    // User Permissions
    Route::put('/users/{user}/permissions',[UsersController::class,'set_permissions']);//->middleware('can:manage_permissions,App\Models\User');
    Route::get('/users/{user}/permissions',[UsersController::class,'get_permissions']);//->middleware('can:view_permissions,App\Models\User');

    // Participant Routes
    Route::get('/participants',[ParticipantsController::class,'get_participants'])->middleware('can:view_participants,App\Models\User');
    Route::get('/participants/{participant}',[ParticipantsController::class,'get_participant']);   // No view implemented
    Route::post('/participants',[ParticipantsController::class,'create_participant'])->middleware('can:manage_participants,App\Models\User');
    Route::put('/participants/{participant}',[ParticipantsController::class,'update_participant'])->middleware('can:manage_participants,App\Models\User');
    Route::delete('/participants/{participant}',[ParticipantsController::class,'delete_participant'])->middleware('can:manage_participants,App\Models\User');

    // Study Routes
    Route::get('/studies',[StudiesController::class,'get_studies'])->middleware('can:view_studies,App\Models\User');
    Route::get('/studies/{study}',[StudiesController::class,'get_study'])->middleware('can:view_study,study');
    Route::post('/studies',[StudiesController::class,'create_study'])->middleware('can:manage_studies,App\Models\User');
    Route::put('/studies/{study}',[StudiesController::class,'update_study'])->middleware('can:manage_study,study');
    Route::delete('/studies/{study}',[StudiesController::class,'delete_study'])->middleware('can:manage_study,study');
    // Study Permissions
    Route::put('/studies/{study}/users/{user}/permissions',[StudiesController::class,'set_study_permissions']);
    Route::get('/studies/{study}/users/{user}/permissions',[StudiesController::class,'get_study_permissions']);

    // Study Participant Routes
    Route::get('/studies/{study}/participants',[StudiesController::class,'get_study_participants'])->middleware('can:view_study,study');
    Route::get('/participants/{participant}/studies',[ParticipantsController::class,'get_participant_studies']); // Who can view this? Restrictions on what is shown?

    Route::post('/studies/{study}/participants/{participant}',[StudiesController::class,'add_study_participant'])->middleware('can:manage_study,study');
    Route::delete('/studies/{study}/participants/{participant}',[StudiesController::class,'delete_study_participant'])->middleware('can:manage_study,study');

    Route::post('/participants/{participant}/studies/{study}',[ParticipantsController::class,'add_participant_study'])->middleware('can:manage_study,study');
    Route::delete('/participants/{participant}/studies/{study}',[ParticipantsController::class,'delete_participant_study'])->middleware('can:manage_study,study');

    // Data Type Routes
    Route::get('/data_types',[DataTypesController::class,'get_data_types'])->middleware('can:view_data_types,App\Models\User');
    Route::get('/data_types/{data_type}',[DataTypesController::class,'get_data_type']); // not found
    Route::post('/data_types',[DataTypesController::class,'create_data_type'])->middleware('can:manage_data_types,App\Models\User');
    Route::put('/data_types/{data_type}',[DataTypesController::class,'update_data_type'])->middleware('can:manage_data_types,App\Models\User'); // not found
    Route::delete('/data_types/{data_type}',[DataTypesController::class,'delete_data_type'])->middleware('can:manage_data_types,App\Models\User'); // not found

    // Study Data Type Routes
    Route::get('/studies/{study}/data_types',[StudiesController::class,'get_study_data_type']);
    Route::get('/data_types/{data_type}/studies',[DataTypesController::class,'get_data_type_studies']);

    Route::post('/studies/{study}/data_types/{data_type}',[StudiesController::class,'add_study_data_type']);
    Route::delete('/studies/{study}/data_types/{data_type}',[StudiesController::class,'delete_study_data_type']);

    Route::post('/data_types/{data_type}/studies/{study}',[DataTypesController::class,'add_data_type_study']);
    Route::delete('/data_types/{data_type}/studies/{study}',[DataTypesController::class,'delete_data_type_study']);

    /* Report Methods */
    Route::get('/reports',[ReportController::class,'get_all_reports']);
    Route::get('/reports/{report}',[ReportController::class,'get_report']);
    Route::post('/reports',[ReportController::class,'add_report']);
    Route::put('/reports/{report}',[ReportController::class,'update_report']);
    Route::delete('/reports/{report}',[ReportController::class,'delete_report']);
    Route::get('/reports/tables',[ReportController::class,'get_tables']);
    Route::get('/reports/tables/columns',[ReportController::class,'get_columns']);
    Route::get('/reports/{report}/execute',[ReportController::class,'execute']);
});
