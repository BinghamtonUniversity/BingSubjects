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
        $permission = Permission::where('user_id',1);
        return $study_permission->where(function ($q) {
            $q->orWhere('study_permission','view_study')
                ->orWhere('study_permission','manage_study');
        }) || $permission->where('permission','view_studies')->first();
    }

    public function manage_study(User $user, Study $study) {
        $study_permission = StudyPermission::where('study_id',$study->id)->where('user_id',1);
        $permission = Permission::where('user_id',1);
        return $study_permission->where('study_permission','manage_study');
        // || $permission->where('permission','manage_studies')->first();
    }


    // Are pivot view/manage permissions necessary? Or does this all fall under view/manage study?
    // public function view_study_participants(User $user){
    //     $permission = Permission::where('user_id',1);
    //     return $permission->where(function ($q){
    //         $q
    //             // ->orWhere('permission','view_users')
    //             // ->orWhere('permission','manage_users')
    //             // ->orWhere('permission','view_permissions')
    //             // ->orWhere('permission','manage_permissions')
    //             // ->orWhere('permission','view_studies')
    //             // ->orWhere('permission','manage_studies')
    //             // ->orWhere('permission','view_participants')
    //             // ->orWhere('permission','manage_participants')
    //             // ->orWhere('permission','view_data_types')
    //             // ->orWhere('permission','manage_data_types');
    //     })->first();
    // }

    // public function manage_study_participants(User $user){
    //     $permission = Permission::where('user_id',1);
    //     return $permission->where(function ($q){
    //         $q
    //             // ->orWhere('permission','view_users')
    //             // ->orWhere('permission','manage_users')
    //             // ->orWhere('permission','view_permissions')
    //             // ->orWhere('permission','manage_permissions')
    //             // ->orWhere('permission','view_studies')
    //             // ->orWhere('permission','manage_studies')
    //             // ->orWhere('permission','view_participants')
    //             // ->orWhere('permission','manage_participants')
    //             // ->orWhere('permission','view_data_types')
    //             // ->orWhere('permission','manage_data_types')
    //     })->first();
    // }

    // public function view_study_data_types(User $user){
    //     $permission = Permission::where('user_id',1);
    //     return $permission->where(function ($q){
    //         $q
    //             // ->orWhere('permission','view_users')
    //             // ->orWhere('permission','manage_users')
    //             // ->orWhere('permission','view_permissions')
    //             // ->orWhere('permission','manage_permissions')
    //             // ->orWhere('permission','view_studies')
    //             // ->orWhere('permission','manage_studies')
    //             // ->orWhere('permission','view_participants')
    //             // ->orWhere('permission','manage_participants')
    //             // ->orWhere('permission','view_data_types')
    //             // ->orWhere('permission','manage_data_types')
    //     })->first();
    // }    
    
    // public function manage_study_data_types(User $user){
    //     $permission = Permission::where('user_id',1);
    //     return $permission->where(function ($q){
    //         $q
    //             // ->orWhere('permission','view_users')
    //             // ->orWhere('permission','manage_users');
    //             // ->orWhere('permission','view_permissions')
    //             // ->orWhere('permission','manage_permissions')
    //             // ->orWhere('permission','view_studies')
    //             // ->orWhere('permission','manage_studies')
    //             // ->orWhere('permission','view_participants')
    //             // ->orWhere('permission','manage_participants')
    //             // ->orWhere('permission','view_data_types')
    //             // ->orWhere('permission','manage_data_types')
    //     })->first();
    // }    
}
