<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Permission;
use App\Models\StudyPermission;
use App\Models\Participant;
use App\Models\Study;
use App\Models\DataType;

class AdminController extends Controller
{
    public function __construct() {
    }

    public function admin(Request $request) {
        // Simulate User 1
        Auth::loginUsingId(1, true);

        return view('default.admin',[
            'page'=>'dashboard',
            'ids'=>[],
            'title'=>'Admin'
        ]);
    }

    public function users(Request $request, User $user=null) { //why is user passed here?
        // Simulate User 1
        $user = User::find(1);

        /* Permissions for User Permissions Options */
        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        /* Actions for Users Page */
        $user_actions = [];
        if ($user->can('manage_users','App\User')) {
            $user_actions[] = ["name"=>"create","label"=>"Create User"];
            $user_actions[] = ["name"=>"edit","label"=>"Update User"];
            $user_actions[] = ["name"=>"delete","label"=>"Delete User","min"=>1];
        }
        if($user->can('view_permissions','App\User')) {
            $user_actions[] = ["name"=>"user_permissions","label"=>"User Permissions","min"=>1,"max"=>1];
        }

        return view('default.admin',[
            'page'=>'users',
            'title'=>'Manage Users',
            'actions' => $user_actions,
            'permissions'=> $auth_user_perms,
            'help'=>
                'Use this page to create, search for, view, delete, and modify existing users.'
        ]);
    }

    public function participants(Request $request) {
        // Simulate User 1
        $user = User::find(1);

        // May be able to pass auth_user_perms to check if manage button is clickable on selected item

        /* Actions for Participants Page */
        $user_actions = [];
        if ($user->can('manage_participants','App\User')) {
            $user_actions[] = ["name"=>"create","label"=>"Create Participant"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Participant"];
            $user_actions[] = ["name"=>"delete","label"=>"Delete Participant","min"=>1];
        }
        $user_actions[] = ["name"=>"participant_studies","label"=>"Manage Participant's Studies","type"=>"primary"];

        return view('default.admin',
            ['page'=>'participants',
            'ids'=>[],
            'actions' => $user_actions,
            'title'=>'Manage Participants',
            'help'=>'Use this page to manage participants.'
        ]);
    }

    public function studies(Request $request, Study $study) {
        // Simulate User 1
        $user = User::find(1);

        // May be able to pass auth_user_perms to check if manage button is clickable on selected item
        // $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();
        // $auth_study_perms = StudyPermission::where('study_id',$study->id)->where('user_id',$user->id)->select('study_permission')->get()->pluck('study_permission')->toArray();
        // $auth_user_perms = array_merge($auth_user_perms, $auth_study_perms);

        /* Actions for Studies Page */
        $user_actions = [];
        if ($user->can('manage_studies','App\User')) {
            $user_actions[] = ["name"=>"create","label"=>"Create Study"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Study"];
            $user_actions[] = ["name"=>"delete","label"=>"Delete Study","min"=>1];
        }
        // Add check if user can manage selected study (or any study before showing buttons to manage)
        $user_actions[] = ["name"=>"study_participants","label"=>"Manage Study's Participants","type"=>"primary"];
        $user_actions[] = ["name"=>"study_data_types","label"=>"Manage Study's Data Types","type"=>"primary"];

        return view('default.admin',
            ['page'=>'studies',
            'ids'=>[],
            'actions' => $user_actions,
            'title'=>'Manage Studies',
            'help'=>'Use this page to manage studies.'
        ]);
    }

    public function study(Request $request, Study $study) {
        // Simulate User 1
        $user = User::find(1);

        /* Study Permissions */
        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();
        $auth_study_perms = StudyPermission::where('study_id',$study->id)->where('user_id',$user->id)->select('study_permission')->get()->pluck('study_permission')->toArray();
        $auth_user_perms = array_merge($auth_user_perms, $auth_study_perms);

        /* Actions for Study Page */
        $user_actions = [];

        return view('default.admin',
            ['page'=>'study',
            'ids'=>$study->id,
            'actions' => $user_actions,
            'permissions'=> $auth_user_perms,
            'title'=>'Manage Study',
            'help'=>'Use this page to manage '.$study->title.'.'
        ]);
    }

    public function study_participants(Request $request, Study $study) {
        // Simulate User 1
        $user = User::find(1);

        /* Permissions for Study's Participants Page */
        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();
        $auth_study_perms = StudyPermission::where('study_id',$study->id)->where('user_id',$user->id)->select('study_permission')->get()->pluck('study_permission')->toArray();
        $auth_user_perms = array_merge($auth_user_perms, $auth_study_perms);

        /* Actions for Study's Participants Page */
        $user_actions = [];
        // Is there a better / preferred way of doing this?
        if (in_array("manage_study", $auth_user_perms) || in_array("manage_studies", $auth_user_perms)) {
            $user_actions[] = ["name"=>"create","label"=>"Add Participant"];
            $user_actions[] = ["name"=>"delete","label"=>"Remove Participant"];
        }

        return view('default.admin',
            ['page'=>'study_participants',
                'id'=>$study->id,
                'actions' => $user_actions,
                'permissions'=> $auth_user_perms,
                'title'=>'Manage Study\'s Participants',
                'help'=>'Use this page to manage participants for '.$study->title.'.'
            ]);
    }

    public function participant_studies(Request $request, Participant $participant, Study $study) {
        // Simulate User 1
        $user = User::find(1);

        // These permissions may not be needed since you only gain access to what you can manage
        /* Permissions for Participant's Studies Page */
        // $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();
        // $auth_study_perms = StudyPermission::where('study_id',$study->id)->where('user_id',$user->id)->select('study_permission')->get()->pluck('study_permission')->toArray();
        // $auth_user_perms = array_merge($auth_user_perms, $auth_study_perms);

        /* Actions for Participant's Studies Page */
        $user_actions[] = ["name"=>"create","label"=>"Add to Study"];
        $user_actions[] = ["name"=>"delete","label"=>"Remove from Study"];
        return view('default.admin',
            ['page'=>'participant_studies',
                'id'=>$participant->id,
                'actions' => $user_actions,
                //'permissions'=> $auth_user_perms,
                'title'=>'Manage Participant\'s Studies',
                'help'=>'Use this page to manage studies for '.$participant->first_name.' '.$participant->last_name.'.'
            ]);
    }

    public function data_types(Request $request){
        // Simulate User 1
        $user = User::find(1);

        /* Permissions for Data Types Page */
        //$auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        /* Actions for Data Types Page */
        $user_actions = [];
        if ($user->can('manage_data_types','App\User')) {
            $user_actions[] = ["name"=>"create","label"=>"Create Data Type"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Data Type"];
            $user_actions[] = ["name"=>"delete", "label"=>"Delete Data Type", "min"=>1];
        }
        $user_actions[] = ["name"=>"data_type_studies", "label"=>"Manage Data Type's Studies", "type"=>"primary"];
        
        return view('default.admin',
            ['page'=>'data_types',
                'actions' => $user_actions,
                //'permissions'=> $auth_user_perms,
                'title'=>'Manage Data Types',
                'help'=>'Use this page to manage data types.'
            ]);
    }

    public function study_data_types(Request $request, Study $study){
        // Simulate User 1
        $user = User::find(1);

        /* Permissions for Study's Data Types Page */
        //$auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();
        $auth_user_perms = StudyPermission::where('study_id',$study->id)->where('user_id',$user->id)->select('study_permission')->get()->pluck('study_permission')->toArray();
        //$auth_user_perms = array_merge($auth_user_perms, $auth_study_perms);

        /* Actions for Study's Data Types Page */
        $user_actions[] = ["name"=>"create","label"=>"Add Data Type"];
        $user_actions[] = ["name"=>"delete","label"=>"Remove Data Type"];
        return view('default.admin',
            ['page'=>'study_data_types',
                'id'=>$study->id,
                'actions' => $user_actions,
                'permissions'=> $auth_user_perms,
                'title'=>'Manage Study\'s Data Types',
                'help'=>'Use this page to manage data types for '.$study->title.'.'
            ]);
    }

    public function data_type_studies(Request $request, DataType $data_type){
        // Simulate User 1
        $user = User::find(1);

        /* Permissions for Data Type's Studies Page */


        /* Actions for Data Type's Studies Page */
        $user_actions[] = ["name"=>"create","label"=>"Add to Study"];
        $user_actions[] = ["name"=>"delete","label"=>"Remove from Study"];
        return view('default.admin',
            ['page'=>'data_type_studies',
                'id'=>$data_type->id,
                'actions' => $user_actions,
                //'permissions'=> $auth_user_perms,
                'title'=>'Manage Data Type\'s Studies',
                'help'=>'Use this page to manage studies with '.$data_type->type.'.'
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reports(Request $request) {
        $user = Auth::user();
        return view('default.admin',['page'=>'reports','ids'=>[],'title'=>'Reports',
            'actions' => [
                ["name"=>"create","label"=>"Create New Report"],
                '',
                ["name"=>"edit","label"=>"Edit Description"],
                ["label"=>"Configure Query","name"=>"configure_query","min"=>1,"max"=>1,"type"=>"default"],
                ["label"=>"Run Report","name"=>"run_report","min"=>1,"max"=>1,"type"=>"warning"],
                '',
                ["name"=>"delete","label"=>"Delete Report"]
            ],
            'help'=>
                'Build and Manage Reports'
        ]);
    }

    /**

     * @param Request $request
     * @param Report $report
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function run_report(Request $request, Report $report) {
        return view('default.admin',['page'=>'reports_execute','id'=>[$report->id],'title'=>$report->name,'help'=>$report->description
        ]);
    }
}
