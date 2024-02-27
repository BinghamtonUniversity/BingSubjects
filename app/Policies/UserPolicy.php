<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use App\Models\StudyPermission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view_users(User $user) {
        return $user->is_study_manager() ||
            Permission::where('user_id',1)->whereIn('permission',[
                'view_users',
                'manage_users',
                'view_permissions',
                'manage_permissions',
                'studies_admin',
            ])->first();
    }

    public function view_permissions(User $user) {
        return Permission::where('user_id',1)->whereIn('permission',[
            'view_permissions',
            'manage_permissions',
        ])->first();
    }

    public function manage_users(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_users')->first();
    }

    public function manage_permissions(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_permissions')->first();
    }
}