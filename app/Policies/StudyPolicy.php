<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Participant;
use App\Models\Permission;
use App\Models\Study;
//use App\Models\StudyDataType;
use App\Models\StudyUser;
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

    public function list_studies(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'create_studies',
                'manage_studies',
                'manage_deletions'
            ])->first();
    }

    public function view_studies(User $user) {
        return Permission::where('user_id',$user->id)->whereIn('permission',[
            'view_studies',
            'manage_studies',
            'manage_deletions'
        ])->first();
    }

    public function create_studies(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','create_studies')->first();
    }

    public function update_studies(User $user) {
        return $user->is_study_manager() ||
            Permission::where('user_id',$user->id)->where('permission','manage_studies')->first();
    }

    public function delete_studies(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_deletions')->first();
    }

    /* Individual Studies */
    public function view_study(User $user, Study $study) {
        return $user->is_study_user($study->id) ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies',
                'manage_deletions'
            ])->first();
    }

    public function manage_study(User $user, Study $study) {
        return $user->is_study_manager($study->id) || 
            Permission::where('user_id',$user->id)->where('permission','manage_studies')->first();
    }
}