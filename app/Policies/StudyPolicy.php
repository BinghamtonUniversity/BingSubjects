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
        return $user->is_study_viewer($study->id) || $user->is_study_manager($study->id) ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies'
            ])->first();
    }

    public function manage_study(User $user, Study $study) {
        return $user->is_study_manager($study->id) || 
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'manage_studies'
            ])->first();
    }

    public function view_studies(User $user) {
        return $user->is_study_viewer() || $user->is_study_manager() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies'
            ])->first();
    }       

    public function manage_studies(User $user) {
        return $user->is_study_manager() || 
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'manage_studies'
            ])->first();
    }

    // Add get / set study permissions?
}