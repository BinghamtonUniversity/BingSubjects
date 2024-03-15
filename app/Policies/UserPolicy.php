<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\StudyUser;
use App\Models\User;

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
                'manage_deletions',
            ])->first();
    }

    public function create_users(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_users')->first();
    }

    public function update_users(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_users')->first();
    }

    public function delete_users(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_deletions')->first();
    }

    public function view_permissions(User $user) {
        return Permission::where('user_id',1)->whereIn('permission',[
            'view_permissions',
            'manage_permissions'
        ])->first();
    }

    public function update_permissions(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_permissions')->first();
    }
}