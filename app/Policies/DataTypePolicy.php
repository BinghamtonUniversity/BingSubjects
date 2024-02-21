<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Study;
use App\Models\StudyPermission;
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

    public function view_data_types(User $user) {
        return $user->is_study_viewer() || $user->is_study_manager() ||
            Permission::where('user_id',1)->whereIn('permission',[
                'view_studies',
                'manage_studies',
                'view_data_types',
                'manage_data_types'
            ])->first();
    }

    public function manage_data_types(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_data_types')->first();
    }
}
