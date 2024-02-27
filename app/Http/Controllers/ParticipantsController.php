<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Study;
use App\Models\StudyParticipant;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Permission;
use App\Models\StudyPermission;

class ParticipantsController extends Controller
{
    public function get_participants(Request $request, User $user) {
        // Hard coding for now
        $user = User::find(1);

        $permission = Permission::where('user_id',1)->select('permission')->get()->pluck('permission');
        if($user->is_study_manager() || 
            $permission->contains('view_participants') || 
            $permission->contains('manage_participants') || 
            $permission->contains('delete_participants') ||
            $permission->contains('view_studies_participants') || 
            $permission->contains('studies_admin')
        ) {
            return Participant::get();
        }
        // If User doesn't have permission to view all participants, then only return participants from studies they can view
        $study_participants = StudyParticipant::whereIn('study_id',$user->study_permissions->pluck('study_id'))
            ->select('participant_id')->get()->pluck('participant_id')->toArray();
        return Participant::whereIn('id',$study_participants)->get();
    }

    public function get_participant(Request $request, Participant $participant) {
        return $participant;
    }

    public function create_participant(Request $request) {
        $participant = new Participant($request->all());
        // Hard coding these values for now until we have authentication and users set up properly.
        $participant->created_by = 1;
        $participant->updated_by = 1;
        $participant->save();
        return $participant;
    }

    public function delete_participant(Request $request, Participant $participant) {
        $participant->delete();
        return 1;
    }

    public function update_participant(Request $request, Participant $participant) {
        $participant->updated_by = 1;
        $participant->update($request->all());
        return 1;
    }

    //START Study Participants Methods
    public function get_participant_studies(Request $request, Participant $participant) {
        // Hard coding for now
        $user = User::find(1);

        $permission = Permission::where('user_id',1)->select('permission')->get()->pluck('permission');
        if($permission->contains('view_studies_participants') || $permission->contains('studies_admin')) {
            return StudyParticipant::where('participant_id',$participant->id)->with('study')->get();
        }
        return StudyParticipant::whereIn('study_id',$user->study_permissions->pluck('study_id'))
            ->where('participant_id',$participant->id)->with('study')->get();
    }

    public function add_participant_study(Request $request, Participant $participant, Study $study) {
        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;

        $study_participant->save();
        return StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->with('study')->first();
    }

    public function delete_participant_study(Request $request, Participant $participant, Study $study) {
        $study_participant = StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->first();
        $study_participant->delete();
        return 1;
    }
    //END Study Participants Methods
}
