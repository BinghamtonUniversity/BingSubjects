<?php

namespace App\Policies;

use App\Models\User;

class DataTypePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function list_data_types(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'create_studies',
                'manage_studies',
                'manage_data_types',
                'manage_deletions'
            ])->first();
    }

    public function create_data_types(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_data_types')->first();
    }

    public function update_data_types(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_data_types')->first();
    }

    public function delete_data_types(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_deletions')->first();
    }
}
