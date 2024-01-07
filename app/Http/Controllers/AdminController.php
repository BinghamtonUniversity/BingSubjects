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
        return view('default.admin',
            ['page'=>'studies',
            'ids'=>[],
            'actions' => $user_actions,
            'title'=>'Manage Studies',
            'help'=>'Use this page to manage studies.'
        ]);
    }

}
