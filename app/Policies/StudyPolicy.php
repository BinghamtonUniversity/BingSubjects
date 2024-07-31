<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Permission;
use App\Models\Study;
use App\Models\User;

class StudyPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function list_studies_sidebar(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies'
            ])->first();
    }

    public function list_search_studies(User $user) {
        return !is_null($user->is_study_user() || Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies'
            ])->first());
    }

    public function manage_studies(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_studies')->first();
    }

    /* Individual Studies */
    public function view_study(User $user, Study $study) {
        return !is_null($user->is_study_user($study->id) ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies'
            ])->first());
    }

    public function manage_study(User $user, Study $study) {
        return !is_null($user->is_study_manager($study->id) ||
            Permission::where('user_id',$user->id)->where('permission','manage_studies')->first());
    }
}
