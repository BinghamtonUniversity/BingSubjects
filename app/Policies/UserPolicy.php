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
    public function list_users_sidebar(User $user){
       return Permission::where('user_id',$user->id)->whereIn('permission',[
            'view_users',
            'manage_users',
        ])->first();
    }
    public function list_search_users(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_users',
                'manage_users',
                'manage_permissions',
                'view_studies',
                'manage_studies',
                'view_participants',
            ])->first();
    }

    public function manage_users(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_users')->first();
    }

    public function manage_permissions(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_permissions')->first();
    }
}
