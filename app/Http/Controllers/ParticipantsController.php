<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Permission;
use App\Models\Study;
use App\Models\StudyParticipant;
use App\Models\StudyUser;
use App\Models\User;

class ParticipantsController extends Controller
{
    public function get_participants(Request $request, User $user) {
        // Hard coding for now
        $user = User::find(1);

        if($user->can('view_participants','App\Participant')) {
            return Participant::get();
        }
        //If User doesn't have permission to view all participants, then only return participants from studies they can view
        $study_participants = StudyParticipant::whereIn('study_id',$user->user_studies->pluck('study_id'))
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

    public function update_participant(Request $request, Participant $participant) {
        $participant->updated_by = 1;
        $participant->update($request->all());
        return $participant;
    }

    public function delete_participant(Request $request, Participant $participant) {
        $participant->delete();
        return 1;
    }

    /* START Study Participant Methods */
    public function get_participant_studies(Request $request, Participant $participant) {
        // Hard coding for now
        $user = User::find(1);

        if($user->can('view_studies','App\Study')) {
            return StudyParticipant::where('participant_id',$participant->id)->with('study')->get();
        }
        // If User doesn't have permission to view all of this participants' study relationships, then only return the studies they can view
        return StudyParticipant::whereIn('study_id',$user->user_studies->pluck('study_id'))
            ->where('participant_id',$participant->id)->with('study')->get();
    }

    public function add_participant_study(Request $request, Participant $participant, Study $study) {
        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;
        $study_participant->save();
        return StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->with('study')->first();
    }

    public function remove_participant_study(Request $request, Participant $participant, Study $study) {
        $study_participant = StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->first();
        $study_participant->delete();
        return 1;
    }
    /* END Study Participant Methods */
}
