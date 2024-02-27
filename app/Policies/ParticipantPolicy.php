<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Study;
use App\Models\StudyPermission;
use App\Models\Permission;

class ParticipantPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view_participants(User $user) {
        return $user->is_study_manager() ||
            Permission::where('user_id',1)->whereIn('permission',[
                'view_participants',
                'manage_participants',
                'delete_participants',
                'view_studies_participants',
                'studies_admin'
            ])->first();
    }

    public function manage_participants(User $user) {
        return Permission::where('user_id',1)->where('permission','manage_participants')->first();
    }

    public function delete_participants(User $user) {
        return Permission::where('user_id',1)->where('permission','delete_participants')->first();
    }


}
