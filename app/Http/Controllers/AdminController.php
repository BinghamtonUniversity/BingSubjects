<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Permission;
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

    public function users(Request $request, User $user=null) {
        // Simulate User 1
        $user = User::find(1);

        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        // Actions for Users Page
        $user_actions = [];
        if ($user->can('manage_users','App\User')) {
            $user_actions[] = ["name"=>"create","label"=>"Create User"];
            $user_actions[] = ["name"=>"edit","label"=>"Update User"];
            $user_actions[] = ["name"=>"delete","label"=>"Delete User","min"=>1];
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
        $user_actions[] = ["name"=>"create","label"=>"New Participant"];
        $user_actions[] = ["name"=>"edit","label"=>"Update Participant"];
        $user_actions[] = ["name"=>"delete","label"=>"Delete Participant","min"=>1];
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

        $auth_user_perms = Permission::where('user_id',$user->id)->select('permission')->get()->pluck('permission')->toArray();

        // Actions for Studies Page
        $user_actions = [];
        if ($user->can('manage_studies','App\Study')) {
            $user_actions[] = ["name"=>"create","label"=>"New Study"];
            $user_actions[] = ["name"=>"edit","label"=>"Update Study"];
            $user_actions[] = ["name"=>"delete","label"=>"Delete Study","min"=>1];
            $user_actions[] = ["name"=>"study_participants","label"=>"Manage Study's Participants","type"=>"primary"];
            $user_actions[] = ["name"=>"study_data_types","label"=>"Manage Study's Data Types","type"=>"primary"];
        }
        if ($user->can('manage_study','App\Study')) {
            $user_actions[] = ["name"=>"edit","label"=>"Update Study"];
            $user_actions[] = ["name"=>"study_participants","label"=>"Manage Study's Participants","type"=>"primary"];
            $user_actions[] = ["name"=>"study_data_types","label"=>"Manage Study's Data Types","type"=>"primary"];
        }
        return view('default.admin',
            ['page'=>'studies',
            'ids'=>[],
            'actions' => $user_actions,
            'permissions'=> $auth_user_perms,
            'title'=>'Manage Studies',
            'help'=>'Use this page to manage studies.'
        ]);
    }

    public function study_participants(Request $request, Study $study){
        $user_actions[] = ["name"=>"create","label"=>"Add Participant"];
        $user_actions[] = ["name"=>"delete","label"=>"Delete Participant"];
        return view('default.admin',
            ['page'=>'study_participants',
                'id'=>$study->id,
                'actions' => $user_actions,
                'title'=>'Manage Study Participants',
                'help'=>'Use this page to manage participants for '.$study->title.'.'
            ]);
    }

    public function participant_studies(Request $request, Participant $participant){
//        dd($participant);
        $user_actions[] = ["name"=>"create","label"=>"Add Study"];
        $user_actions[] = ["name"=>"delete","label"=>"Delete Study"];
        return view('default.admin',
            ['page'=>'participant_studies',
                'id'=>$participant->id,
                'actions' => $user_actions,
                'title'=>'Manage Participant Studies',
                'help'=>'Use this page to manage studies for '.$participant->first_name.' '.$participant->last_name.'.'
            ]);
    }

    public function data_types(Request $request){
        $user_actions[] = ["name"=>"create","label"=>"New"];
        $user_actions[] = ["name"=>"edit","label"=>"Update"];
        $user_actions[] = ["name"=>"delete", "label"=>"Delete", "min"=>1];
        $user_actions[] = ["name"=>"data_type_studies", "label"=>"Manage Data Type's Studies", "type"=>"primary"];
        return view('default.admin',
            ['page'=>'data_types',
                'actions' => $user_actions,
                'title'=>'Manage Data Types',
                'help'=>'Use this page to manage data types.'
            ]);
    }

    public function study_data_types(Request $request, Study $study){
        $user_actions[] = ["name"=>"create","label"=>"Add Data Type"];
        $user_actions[] = ["name"=>"delete","label"=>"Delete Data Type"];
        return view('default.admin',
            ['page'=>'study_data_types',
                'id'=>$study->id,
                'actions' => $user_actions,
                'title'=>'Manage Study Data Types',
                'help'=>'Use this page to manage data types for '.$study->title.'.'
            ]);
    }

    public function data_type_studies(Request $request, DataType $data_type){
        $user_actions[] = ["name"=>"create","label"=>"Add Study"];
        $user_actions[] = ["name"=>"delete","label"=>"Delete Study"];
        return view('default.admin',
            ['page'=>'data_type_studies',
                'id'=>$data_type->id,
                'actions' => $user_actions,
                'title'=>'Manage Data Type Studies',
                'help'=>'Use this page to manage studies with '.$data_type->type.'.'
            ]);
    }
}
