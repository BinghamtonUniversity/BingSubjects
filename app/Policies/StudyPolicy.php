<?php

namespace App\Policies;

use App\Models\Study;
use App\Models\StudyPermission;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudyPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view_study(User $user, Study $study) {
        $study_permission = StudyPermission::where('study_id',$study->id)->where('user_id',1);  //$user->id
        //$permission = Permission::where('user_id',1);
        return $study_permission->where(function ($q) {
            $q->orWhere('study_permission','view_study')
                ->orWhere('study_permission','manage_study');
        })->first();
        //|| $permission->where('permission','view_studies')->first();
    }

    public function manage_study(User $user, Study $study) {
        $study_permission = StudyPermission::where('study_id',$study->id)->where('user_id',1);
        //$permission = Permission::where('user_id',1);
        return $study_permission->where('study_permission','manage_study');
        // || $permission->where('permission','manage_studies')->first();
    }
}