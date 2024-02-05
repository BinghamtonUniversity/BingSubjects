<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
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

    // START User Permissions
    public function view_users(User $user) {
        $permission = Permission::where('user_id',1);    //$user->id
        return $permission->where(function ($q) {
            $q
                ->orWhere('permission','view_users')
                ->orWhere('permission','manage_users')
                ->orWhere('permission','view_permissions')
                ->orWhere('permission','manage_permissions')
                ->orWhere('permission','view_studies')
                ->orWhere('permission','manage_studies');
                // ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();
    }

    public function view_permissions(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                ->orWhere('permission','view_permissions')
                ->orWhere('permission','manage_permissions');
                // ->orWhere('permission','view_studies')
                // ->orWhere('permission','manage_studies')
                // ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();
    }

    public function view_studies(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                ->orWhere('permission','view_studies')
                ->orWhere('permission','manage_studies')
                ->orWhere('permission','view_participants')
                ->orWhere('permission','manage_participants')
                ->orWhere('permission','view_data_types')
                ->orWhere('permission','manage_data_types');
        })->first();
    }

    public function view_participants(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                // ->orWhere('permission','view_studies')
                // ->orWhere('permission','manage_studies')
                ->orWhere('permission','view_participants')
                ->orWhere('permission','manage_participants');
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();
    }

    public function view_data_types(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                ->orWhere('permission','view_studies')
                ->orWhere('permission','manage_studies')
                ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                ->orWhere('permission','view_data_types')
                ->orWhere('permission','manage_data_types');
        })->first();
    }
    // END User Permissions
    
    // START Global Permissions
    public function manage_users(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                ->orWhere('permission','manage_users');
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                // ->orWhere('permission','view_studies')
                // ->orWhere('permission','manage_studies')
                // ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();
    }

    public function manage_permissions(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                ->orWhere('permission','manage_permissions');
                // ->orWhere('permission','view_studies')
                // ->orWhere('permission','manage_studies')
                // ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();
    }

    public function manage_studies(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                // ->orWhere('permission','view_studies')
                ->orWhere('permission','manage_studies');
                // ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();    // I don't need to add manage_study from the study permissions here, right?
    }

    public function manage_participants(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                // ->orWhere('permission','view_studies')
                // ->orWhere('permission','manage_studies');
                // ->orWhere('permission','view_participants')
                ->orWhere('permission','manage_participants');
                // ->orWhere('permission','view_data_types')
                // ->orWhere('permission','manage_data_types')
        })->first();
    }

    public function manage_data_types(User $user) {
        $permission = Permission::where('user_id',1);
        return $permission->where(function ($q) {
            $q
                // ->orWhere('permission','view_users')
                // ->orWhere('permission','manage_users')
                // ->orWhere('permission','view_permissions')
                // ->orWhere('permission','manage_permissions')
                // ->orWhere('permission','view_studies')
                // ->orWhere('permission','manage_studies')
                // ->orWhere('permission','view_participants')
                // ->orWhere('permission','manage_participants')
                // ->orWhere('permission','view_data_types')
                ->orWhere('permission','manage_data_types');
        })->first();
    }  
    // END Global Permissions  
}