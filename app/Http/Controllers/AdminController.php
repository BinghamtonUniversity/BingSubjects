<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\DataType;
use App\Models\Participant;
use App\Models\Permission;
use App\Models\Report;
use App\Models\Study;
use App\Models\StudyDataType;
use App\Models\StudyParticipant;
use App\Models\StudyUser;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct() {
    }

    public function admin(Request $request) {
        $user = Auth::user();

        if($user->can('list_users_sidebar','App\User')){
            return redirect('/users');
        }elseif ($user->can('list_studies_sidebar','App\Studies')){
            return redirect('/studies');
        }elseif ($user->can('list_reports_sidebar','App\Reports')){
            return redirect('/reports');
        }elseif ($user->can('list_participants_sidebar','App\Participants')){
            return redirect('/participants');
        }elseif ($user->can('list_datatypes_sidebar','App\Datatypes')){
            return redirect('/datatypes');
        }
        else{
            Auth::logout();
        }
    }

    /* Users Tab */
    public function users(Request $request, User $user=null) {
        $user = Auth::user();

        /* Permissions for User Permissions Options */
        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        /* Actions for Users Page */
        $user_actions = [];
        if ($user->can('manage_users','App\User')) {
            $user_actions[] = ["name"=>"create","label"=>"Create User"];
            $user_actions[] = ["name"=>"edit","label"=>"Update User","min"=>1,"max"=>1];
            $user_actions[] = ["name"=>"activate_user","label"=>"Activate User",'type'=>'success',"min"=>1,"max"=>5];
            $user_actions[] = ["name"=>"deactivate_user","label"=>"Deactivate User",'type'=>'danger',"min"=>1,"max"=>5];
            $user_actions[] = [""];
            $user_actions[] = [""];
            $user_actions[] = ["name"=>"delete","label"=>"Delete User","min"=>1,"max"=>1]; //may remove max
        }
        if ($user->can('manage_users','App\User') && $user->can('view_permissions','App\User')) {
            $user_actions[] = [];
        }
        if($user->can('manage_permissions','App\User')) {
            $user_actions[] = ["name"=>"user_permissions","label"=>"User Permissions","min"=>1,"max"=>1];
        }

        return view('default.admin',[
            'page'=>'users',
            'title'=>'Manage Users',
            'actions'=>$user_actions,
            'permissions'=>$auth_user_perms,
            'help'=>'Use this page to create, search for, view, delete, and modify existing users.'
        ]);
    }

    /* Participants Tab */
    public function participants(Request $request) {
        $user = Auth::user();

        /* Actions for Participants Page */
        $user_actions = [];
        if ($user->can('update_participants','App\Participant')) {
            $user_actions[] = ["name"=>"create","label"=>"Create Participant"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Participant","min"=>1,"max"=>1];
        }
        if ($user->can('manage_participants','App\Participant')) {
            $user_actions[] = ["name"=>"delete","label"=>"Delete Participant","min"=>1,"max"=>5];
        }
        if (($user->can('update_participants','App\Participant') || $user->can('manage_participants','App\Participant')) && ($user->is_study_user() || $user->can('view_studies','App\Study'))) {
            $user_actions[] = [];
        }
        // Update to only be clickable depending on if the user can view any studies of the participant checked
        if ($user->is_study_user() || $user->can('view_studies','App\Study')) {
            $user_actions[] = ["name"=>"participant_studies","label"=>"Participant's Studies","min"=>1,"max"=>1];
        }

        return view('default.admin',[
            'page'=>'participants',
            'ids'=>[],
            'actions'=>$user_actions,
            'title'=>'Manage Participants',
            'help'=>'Use this page to manage participants.'
        ]);
    }

    public function participant_studies(Request $request, Participant $participant) {
        $user = Auth::user();

        /* Actions for Participant's Studies Page */
        $user_actions = [];
        if($user->can('manage_studies','App\Study') || $user->is_study_manager()) {
            $user_actions[] = ["name"=>"create","label"=>"Add to Study"];
            $user_actions[] = ["name"=>"delete","label"=>"Remove from Study"];
        }

        return view('default.admin',[
            'page'=>'participant_studies',
            'id'=>[$participant->id,$user->id],
            'actions'=>$user_actions,
            'title'=>'Manage Participant\'s Studies',
            'help'=>'Use this page to manage studies for '.$participant->first_name.' '.$participant->last_name.'.'
        ]);
    }

    /* Studies Tab */
    public function studies(Request $request) {
        $user = Auth::user();

        /* Permissions for User Permissions Options */
        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        /* Actions for Studies Page */
        $user_actions = [];
        if($user->is_study_user() || $user->can('list_search_studies','App\Study')) {
            $user_actions[] = ["name"=>"edit","label"=>"View Study","type"=>"warning","min"=>1,"max"=>1];
        }

        $user_actions[] = [];

        if ($user->can('manage_studies','App\Study')) {
            $user_actions[] = ["name"=>"create","label"=>"Create Study"];
        }
        if ($user->can('manage_studies','App\Study')) {
            $user_actions[] = ["name"=>"delete","label"=>"Delete Study","min"=>1,"max"=>1]; //may remove max
        }

        return view('default.admin',[
            'page'=>'studies',
            'id'=>$user->id,
            'actions'=>$user_actions,
            'permissions'=>$auth_user_perms,
            'title'=>'Manage Studies',
            'help'=>'Use this page to manage studies.'
        ]);
    }

    public function study(Request $request, Study $study) {
        $user = Auth::user();

        /* Permissions for Study Page */
        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        /* Actions for Study Page */
        $user_actions = [];
        $user_actions_manage = false;
        $user_actions_data_types = [];
        $user_actions_participants = [];
        $user_actions_users = [];

        if($user->can('manage_study',$study)) {
            $user_actions_manage = true;
            // Data Types Actions
            $user_actions_data_types[] = ["name"=>"add_data_type","label"=>"Add Data Type"];
            $user_actions_data_types[] = ["name"=>"edit_data_type","label"=>"Update Data Type", "min"=>1, "max"=>1];
            $user_actions_data_types[] = ["name"=>"delete","label"=>"Remove Data Type"];
            // Participants Actions
            $user_actions_participants[] = ["name"=>"add_participant","label"=>"Add Participant"];
            $user_actions_participants[] = ["name"=>"delete","label"=>"Remove Participant"];
            // Users Actions
            $user_actions_users[] = ["name"=>"add_user","label"=>"Add User"];
            $user_actions_users[] = ["name"=>"edit","label"=>"Update User Permissions", "min"=>1, "max"=>1];
            $user_actions_users[] = ["name"=>"delete","label"=>"Remove User"];
        }
        $user_actions = [
            'manage'=>$user_actions_manage,
            'data_types'=>$user_actions_data_types,
            'participants'=>$user_actions_participants,
            'users'=>$user_actions_users
        ];

        return view('default.admin',[
            'page'=>'study',
            'id'=>$study->id,
            'actions'=>$user_actions,
            'permissions'=>$auth_user_perms,
            'title'=>'Manage Study',
            'help'=>'Use this page to manage '.$study->title.'.'
        ]);
    }

    public function study_participants(Request $request, Study $study) {
        $user = Auth::user();

        /* Actions for Study's Participants Page */
        $user_actions = [];
        if($user->can('manage_study',$study)) {
            $user_actions[] = ["name"=>"create","label"=>"Add Participant"];
            $user_actions[] = ["name"=>"delete","label"=>"Remove Participant"];
        }

        return view('default.admin',[
            'page'=>'study_participants',
            'id'=>$study->id,
            'actions'=>$user_actions,
            'title'=>'Manage Study\'s Participants',
            'help'=>'Use this page to manage participants for '.$study->title.'.'
        ]);
    }

    public function study_data_types(Request $request, Study $study) {
        $user = Auth::user();

        /* Actions for Study's Data Types Page */
        $user_actions = [];
        if($user->can('manage_study',$study)) {
            $user_actions[] = ["name"=>"create","label"=>"Add Data Type"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Data Type", "min"=>1];
            $user_actions[] = ["name"=>"delete","label"=>"Remove Data Type", "min"=>1];
        }

        return view('default.admin',[
            'page'=>'study_data_types',
            'id'=>$study->id,
            'actions'=>$user_actions,
            'title'=>'Manage Study\'s Data Types',
            'help'=>'Use this page to manage data types for '.$study->title.'.'
        ]);
    }

    public function study_users(Request $request, Study $study) {
        $user = Auth::user();

        /* Actions for Study's Participants Page */
        $user_actions = [];
        if($user->can('manage_study',$study)) {
            $user_actions[] = ["name"=>"create","label"=>"Add User"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Type", "min"=>1, "max"=>1];
            $user_actions[] = ["name"=>"delete","label"=>"Remove User"];
        }

        return view('default.admin',[
            'page'=>'study_users',
            'id'=>$study->id,
            'actions'=>$user_actions,
            'title'=>'Manage Study\'s Users',
            'help'=>'Use this page to manage users for '.$study->title.'.'
        ]);
    }

    /* Data Types Tab */
    public function data_types(Request $request){
        $user = Auth::user();

        /* Actions for Data Types Page */
        $user_actions = [];
        if ($user->can('manage_datatypes','App\DataType')) {
            $user_actions[] = ["name"=>"create","label"=>"Create Data Type"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Data Type", "min"=>1, "max"=>1];
            $user_actions[] = ["name"=>"delete","label"=>"Delete Data Type","min"=>1];
        }

        return view('default.admin',[
            'page'=>'data_types',
            'actions'=>$user_actions,
            'title'=>'Manage Data Types',
            'help'=>'Use this page to manage data types.'
        ]);
    }

    /* Reports Tab */

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reports(Request $request) {
        $user = Auth::user();

        /* Actions for Data Types Page */
        $user_actions = [];
        if ($user->can('manage_reports','App\Report') ||
            in_array('run_reports',$user->permissions) || !is_null(Report::where('owner_user_id',$user->id)->first())) {
            $user_actions[] = ["name"=>"create","label"=>"Create New Report"];
            $user_actions[] = [""];
            $user_actions[] = ["name"=>"edit","label"=>"Update Report", "min"=>1, "max"=>1];
            $user_actions[] = ["label"=>"Configure Query","name"=>"configure_query","min"=>1,"max"=>1,"type"=>"default"];
            $user_actions[] = [""];
            $user_actions[] = ["name"=>"delete","label"=>"Delete Report","min"=>1];
        }

        if ($user->can('run_reports','App\Report')) {
            $user_actions[] = ["label"=>"Run Report","name"=>"run_report","min"=>1,"max"=>1,"type"=>"warning"];
        }


        return view('default.admin',[
            'page'=>'reports',
            'ids'=>[],
            'title'=>'Reports',
            'actions' => $user_actions,
            'help'=>'Build and Manage Reports'
        ]);
    }

    /**

     * @param Request $request
     * @param Report $report
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function run_report(Request $request, Report $report) {
        return view('default.admin',[
            'page'=>'reports_execute',
            'id'=>[$report->id],
            'title'=>$report->name,
            'help'=>$report->description
        ]);
    }
}
