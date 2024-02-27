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

    public function create_data_types(User $user) {
        return Permission::where('user_id',1)->whereIn('permission',[
            'create_data_types',
            'manage_data_types',
        ])->first();
    }

    public function manage_data_types(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_data_types')->first();
    }
}
