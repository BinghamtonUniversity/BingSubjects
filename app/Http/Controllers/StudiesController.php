<?php

namespace App\Http\Controllers;

use App\Models\Study;
use App\Models\StudyParticipant;
use App\Models\Participant; // Is there a way to not include this?
use Illuminate\Http\Request;

class StudiesController extends Controller
{
    public function get_studies(Request $request) {
        return Study::get();
    }
    public function get_study(Request $request, Study $study) {
        return $study->first();
    }


    public function create_study(Request $request) {
        $study = new Study($request->all());
        // Hard coding these values for now until we have authentication and users set up properly.
        $study->created_by = 1;
        $study->updated_by = 1;
        $study->save();
        return $study->first();
    }

    public function delete_study(Request $request, Study $study) {
        $study->delete();
        return 1;
    }

    public function update_study(Request $request, Study $study) {
        $study->updated_by = 1;
        $study->update($request->all());
        return $study;
    }

    public function add_study_participant(Request $request, Study $study, Participant $participant) {
        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;

        $study_participant->save();
        return $study_participant;
    }
}
