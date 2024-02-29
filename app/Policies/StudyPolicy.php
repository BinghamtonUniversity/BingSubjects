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
                'view_studies_participants',
                'studies_admin'
            ])->first();
    }

    public function manage_study(User $user, Study $study) {
        return $user->is_study_manager($study->id) || 
            Permission::where('user_id',$user->id)->where('permission','studies_admin')->first();
    }

    public function manage_studies(User $user) {
        return $user->is_study_manager() || 
            Permission::where('user_id',$user->id)->where('permission','studies_admin')->first();
    }

    public function view_studies(User $user) {
        return Permission::where('user_id',$user->id)->whereIn('permission',[
            'view_studies',
            'create_studies',
            'view_studies_participants',
            'studies_admin'
        ])->first();
    }

    public function view_studies_participants(User $user) {
        return $user->is_study_manager() || $user->is_study_viewer() ||
        Permission::where('user_id',$user->id)->whereIn('permission',[
            'view_studies_participants',
            'studies_admin'
        ])->first();
    }

    public function create_studies(User $user) {
        return Permission::where('user_id',$user->id)->whereIn('permission',[
            'create_studies',
            'studies_admin',
        ])->first();
    }

    public function view_data_types(User $user) {
        return $user->is_study_manager() || $user->is_study_viewer() || 
        Permission::where('user_id',$user->id)->whereIn('permission',[
            'view_studies',
            'create_studies',
            'view_studies_participants',
            'create_data_types',
            'manage_data_types',
            'studies_admin'
        ])->first();
    }

    public function studies_admin(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','studies_admin')->first();
    }

    // Add get / set study permissions?
}