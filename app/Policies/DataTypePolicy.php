<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission;

class DataTypePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function list_datatypes_sidebar(User $user){
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'manage_data_types'
            ])->first();
    }
    public function list_search_datatypes(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'manage_studies',
                'manage_data_types'
            ])->first();
    }

    public function manage_datatypes(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_data_types')->first();
    }
}
