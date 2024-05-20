<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Permission;
use App\Models\User;
use App\Models\Report;

class ReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function list_reports_sidebar(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_reports',
                'manage_reports',
                'run_reports'
            ])->first();
    }


//    public function view_studies(User $user) {
//        return Permission::where('user_id',$user->id)->whereIn('permission',[
//            'view_studies',
//            'manage_studies'
//        ])->first();
//    }


//    public function manage_studies(User $user) {
//        return Permission::where('user_id',$user->id)->where('permission','manage_studies')->first();
//    }
//
//    /* Individual Studies */
//    public function view_study(User $user, Study $study) {
//        return $user->is_study_user($study->id) ||
//            Permission::where('user_id',$user->id)->whereIn('permission',[
//                'view_studies',
//                'manage_studies'
//            ])->first();
//    }
//
//    public function manage_study(User $user, Study $study) {
//        return $user->is_study_manager($study->id) ||
//            Permission::where('user_id',$user->id)->where('permission','manage_studies')->first();
//    }
}
