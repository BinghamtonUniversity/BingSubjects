<?php

namespace App\Http\Controllers;

use App\Models\Study;
use App\Models\StudyParticipant;
use Illuminate\Http\Request;
use App\Models\Participant;

class ParticipantsController extends Controller
{
    public function get_participants(Request $request) {
        return Participant::all();
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
        return StudyParticipant::where('participant_id',$participant->id)->with('study')->get();
    }

    public function add_participant_study(Request $request, Participant $participant, Study $study){
        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;

        $study_participant->save();
        return StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->with('study')->first();
    }

    public function delete_participant_study(Request $request, Participant $participant, Study $study){
        $study_participant = StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->first();
        $study_participant->delete();
        return 1;
    }
    //END Study Participants Methods
}
