<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Permission;
use App\Models\Participant;
use App\Models\Study;

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

    public function participants(Request $request) {
        $user_actions[] = ["name"=>"create","label"=>"New Participant"];
        $user_actions[] = ["name"=>"edit","label"=>"Update Participant"];
        $user_actions[] = ["label"=>"Delete Participant",'name'=>'delete', 'min'=>1];
        $user_actions[] = ["label"=>"Participants Studies",'name'=>'participant_studies', 'type'=>'primary'];
        return view('default.admin',
            ['page'=>'participants',
            'ids'=>[],
            'actions' => $user_actions,
            'title'=>'Manage Participants',
            'help'=>'Use this page to manage participants.'
        ]);
    }

    public function studies(Request $request) {
        $user_actions[] = ["name"=>"create","label"=>"New Study"];
        $user_actions[] = ["name"=>"edit","label"=>"Update Study"];
        $user_actions[] = ["label"=>"Delete Study",'name'=>'delete', 'min'=>1];
        $user_actions[] = ["label"=>"Study Participants",'name'=>'study_participants', 'type'=>'primary'];
        return view('default.admin',
            ['page'=>'studies',
            'ids'=>[],
            'actions' => $user_actions,
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
                'help'=>'Use this page to manage study participants for '.$study->title
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
                'help'=>'Use this page to manage study participants for '.$participant->first_name.' '.$participant->last_name
            ]);
    }

}
