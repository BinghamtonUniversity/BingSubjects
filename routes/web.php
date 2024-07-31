<?php

use App\Http\Controllers\CASController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\StudiesController;
use App\Http\Controllers\DataTypesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReportController;

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
Route::any('/login', [CASController::class, 'login']);
Route::get('/logout',[CASController::class, 'logout']);

Route::group(['middleware'=>['custom.auth']], function () {
    Route::get('/',[AdminController::class,'admin']);
    Route::get('/users/{user?}',[AdminController::class,'users']);
    Route::get('/participants',[AdminController::class,'participants']);
    Route::get('/studies',[AdminController::class,'studies']);
    Route::get('/studies/{study}',[AdminController::class,'study']);
    Route::get('/studies/{study}/users',[AdminController::class,'study_users']);
    Route::get('/studies/{study}/participants',[AdminController::class,'study_participants']);
    Route::get('/studies/{study}/data_types',[AdminController::class,'study_data_types']);
    Route::get('/participants/{participant}/studies',[AdminController::class,'participant_studies']);
    Route::get('/data_types',[AdminController::class,'data_types']);
    Route::get('/reports',[AdminController::class,'reports']);
    Route::get('/reports/{report}/run',[AdminController::class,'run_report']);

    Route::group(['prefix' => 'api'], function () {
        /* User Routes */
        Route::get('/users/search/{search_string?}',[UsersController::class,'search'])->middleware("can:list_search_users, App\Models\User");
        Route::get('/users',[UsersController::class,'get_users'])->middleware('can:list_search_users,App\Models\User');
        Route::get('/users/{user}',[UsersController::class,'get_user'])->middleware('can:list_search_users,App\Models\User');
        Route::post('/users',[UsersController::class,'create_user'])->middleware('can:manage_users,App\Models\User');
        Route::put('/users/{user}',[UsersController::class,'update_user'])->middleware('can:manage_users,App\Models\User');
        Route::delete('/users/{user}',[UsersController::class,'delete_user'])->middleware('can:manage_users,App\Models\User');

        /* User Permission Routes */
        Route::put('/users/{user}/permissions',[UsersController::class,'set_permissions'])->middleware('can:manage_permissions,App\Models\User');
        Route::get('/users/{user}/permissions',[UsersController::class,'get_permissions'])->middleware('can:manage_permissions,App\Models\User');

        /* Participant Routes */
        Route::get('/participants',[ParticipantsController::class,'get_participants'])->middleware('can:list_participants,App\Models\Participant');
        Route::post('/participants',[ParticipantsController::class,'create_participant'])->middleware('can:update_participants,App\Models\Participant');
        Route::put('/participants/{participant}',[ParticipantsController::class,'update_participant'])->middleware('can:manage_participants,App\Models\Participant');
        Route::delete('/participants/{participant}',[ParticipantsController::class,'delete_participant'])->middleware('can:manage_participants,App\Models\Participant');

        /* Study Routes */
        Route::get('/studies',[StudiesController::class,'list_studies'])->middleware('can:list_search_studies,App\Models\Study');
        Route::get('/studies/{study}',[StudiesController::class,'get_study'])->middleware('can:list_search_studies,App\Models\Study');
        Route::post('/studies',[StudiesController::class,'create_study'])->middleware('can:manage_studies,App\Models\Study');
        Route::put('/studies/{study}',[StudiesController::class,'update_study'])->middleware('can:manage_studies,App\Models\Study');
        Route::delete('/studies/{study}',[StudiesController::class,'delete_study'])->middleware('can:manage_studies,App\Models\Study'); //->middleware('can:manage_studies,App\Models\Study');

        /* Data Type Routes */
        Route::get('/data_types',[DataTypesController::class,'list_data_types'])->middleware('can:list_search_datatypes,App\Models\DataType');
        Route::get('/data_types/{data_type}',[DataTypesController::class,'get_data_type'])->middleware('can:list_search_datatypes,App\Models\DataType');
        Route::post('/data_types',[DataTypesController::class,'create_data_type'])->middleware('can:manage_datatypes,App\Models\DataType');
        Route::put('/data_types/{data_type}',[DataTypesController::class,'update_data_type'])->middleware('can:manage_datatypes,App\Models\DataType');
        Route::delete('/data_types/{data_type}',[DataTypesController::class,'delete_data_type'])->middleware('can:manage_datatypes,App\Models\DataType');

        /* Study User Routes */
        Route::get('/studies/users/{user}',[StudiesController::class,'get_manageable_studies'])->middleware('can:list_search_studies,App\Models\Study');
        Route::get('/studies/{study}/users',[StudiesController::class,'get_study_users'])->middleware('can:view_study,study');

        Route::post('/studies/{study}/users/{user}',[StudiesController::class,'add_study_user'])->middleware('can:manage_study,study');
        Route::put('/studies/{study}/users/{user}',[StudiesController::class,'update_study_user'])->middleware('can:manage_study,study');
        Route::delete('/studies/{study}/users/{user}',[StudiesController::class,'remove_study_user'])->middleware('can:manage_study,study');

        /* Study Participant Routes */
        Route::get('/studies/{study}/participants',[StudiesController::class,'get_study_participants'])->middleware('can:view_study,study');
        Route::post('/studies/{study}/participants/{participant}',[StudiesController::class,'add_study_participant'])->middleware('can:manage_study,study');
        Route::delete('/studies/{study}/participants/{participant}',[StudiesController::class,'remove_study_participant'])->middleware('can:manage_study,study');
        Route::get('/participants/{participant}/studies',[ParticipantsController::class,'get_participant_studies'])->middleware('can:view_participant_studies,participant');
        Route::post('/participants/{participant}/studies/{study}',[ParticipantsController::class,'add_participant_study'])->middleware('can:manage_study,study');
        Route::delete('/participants/{participant}/studies/{study}',[ParticipantsController::class,'remove_participant_study'])->middleware('can:manage_study,study');

        /* Study Data Type Routes */
        Route::get('/studies/{study}/data_types',[StudiesController::class,'get_study_data_types'])->middleware('can:view_study,study');
        Route::post('/studies/{study}/data_types/{data_type}',[StudiesController::class,'add_study_data_type'])->middleware('can:manage_study,study');
        Route::put('/studies/{study}/data_types/{study_data_type}',[StudiesController::class,'update_study_data_type'])->middleware('can:manage_study,study');
        Route::delete('/studies/{study}/data_types/{study_data_type}',[StudiesController::class,'remove_study_data_type'])->middleware('can:manage_study,study');

        /* Report Methods */
        Route::get('/reports',[ReportController::class,'get_all_reports'])->middleware('can:list_search_reports,App\Models\Report');
        Route::get('/reports/{report}',[ReportController::class,'get_report'])->middleware('can:list_search_reports,App\Models\Report');
        Route::post('/reports',[ReportController::class,'add_report'])->middleware('can:manage_reports,App\Models\Report');
        Route::put('/reports/{report}',[ReportController::class,'update_report'])->middleware('can:manage_report,report');
        Route::delete('/reports/{report}',[ReportController::class,'delete_report'])->middleware('can:manage_reports,App\Models\Report');
        Route::get('/reports/tables',[ReportController::class,'get_tables'])->middleware('can:list_search_reports,App\Models\Report');
        Route::get('/reports/tables/columns',[ReportController::class,'get_columns'])->middleware('can:list_search_reports,App\Models\Report');
        Route::get('/reports/{report}/execute',[ReportController::class,'execute'])->middleware('can:run_report,report');
    });
});
