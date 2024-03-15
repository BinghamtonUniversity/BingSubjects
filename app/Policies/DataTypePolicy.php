<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Study;
use App\Models\StudyUser;
use App\Models\User;

class DataTypePolicy
{
    /**
     * Create a new policy instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    // public function view_data_types(User $user) {
    //     return Permission::where('user_id',1)->whereIn('permission',[
    //         'view_studies_info',
    //         'view_studies',
    //         'create_studies',
    //         'manage_studies',
    //         // 'create_data_types',
    //         // 'manage_data_types',
    //         'manage_deletions'
    //     ])->first();
    // }

    // public function create_data_types(User $user) {
    //     return Permission::where('user_id',1)->whereIn('permission',[
    //         'create_data_types',
    //         'manage_data_types',
    //     ])->first();
    // }

    // public function update_data_types(User $user) {
    //     return Permission::where('user_id',1)->where('permission','manage_data_types')->first();
    // }

    // public function delete_data_types(User $user) {
    //     return Permission::where('user_id',1)->where('permission','manage_deletions')->first();
    // }
}
