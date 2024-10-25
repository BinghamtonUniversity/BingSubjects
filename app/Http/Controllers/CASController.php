<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CASController extends Controller {
    public function login(Request $request) {
        if(!Auth::check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }
            cas()->authenticate();
        }
        $identity_attributes = cas()->getAttributes();
        $identity = User::where('bnumber',$identity_attributes['UDC_IDENTIFIER'])
            ->where('active',true)->where(function ($q){
          $q->where('will_expire',false)->orWhere(function($qw){
              $qw->where('will_expire',true)->where('expiration_date','>=',Carbon::today());
          });
        })->first();
        if (is_null($identity)) {
            return response('Unauthorized.', 401);
        }
        Auth::login($identity,true);
        if ($request->has('redirect')) {
            return redirect($request->redirect);
        } else {
            return redirect('/');
        }
    }

    public function logout(Request $request){
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            if (cas()->checkAuthentication()) {
                cas()->logout();
            }
        } else {
            return response('You are not logged in.', 401);
        }
    }
}
