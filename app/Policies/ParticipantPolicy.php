<?php

namespace App\Policies;

use App\Models\Participant;
use App\Models\Permission;
use App\Models\Study;
use App\Models\StudyParticipant;
use App\Models\StudyUser;
use App\Models\User;

class ParticipantPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function list_participants(User $user) {
        return $user->is_study_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_studies',
                'create_studies',
                'manage_studies',
                'view_participants',
                'manage_participants',
                'manage_deletions'
            ])->first();
    }

    public function view_participants(User $user) {
        return $user->is_study_manager() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_participants',
                'manage_participants',
                'manage_deletions'
            ])->first();
    }

    public function create_participants(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_participants')->first();
    }

    public function update_participants(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_participants')->first();
    }

    public function delete_participants(User $user) {
        return Permission::where('user_id',$user->id)->where('permission','manage_deletions')->first();
    }

    public function view_participant_studies(User $user, Participant $participant) {
        $participant_studies = StudyParticipant::whereIn('study_id',$user->user_studies->pluck('study_id'))
            ->where('participant_id',$participant->id)->select('study_id')->get()->pluck('study_id')->toArray();

        return $user->is_study_user($participant_studies) ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'manage_studies',
                'manage_deletions'
            ])->first();
    }
}