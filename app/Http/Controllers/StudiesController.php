<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Study;
use App\Models\Participant;
use App\Models\StudyParticipant;
use App\Models\DataType;
use App\Models\StudyDataType;
use App\Models\StudyPermission;
use Illuminate\Http\Request;

class StudiesController extends Controller
{
    public function get_studies(Request $request) {
        return Study::get();
    }
    public function get_study(Request $request, Study $study) {
        return $study;
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

    //START Study Participants Methods
    public function get_study_participants(Request $request, Study $study) {
        return StudyParticipant::where('study_id',$study->id)->with('participant')->get();
    }

    public function add_study_participant(Request $request, Study $study, Participant $participant) {
        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;

        $study_participant->save();
        return StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->with('study')->first();
    }

    public function delete_study_participant(Request $request, Study $study, Participant $participant) {
        $study_participant = StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->first();
        $study_participant->delete();
        return 1;
    }
    //END Study Participants Methods


    //START Study Data Types Methods
    public function get_study_data_types(Request $request, Study $study) {
        return StudyDataType::where('study_id',$study->id)->with('data_type')->get();
    }

    public function add_study_data_type(Request $request, Study $study, DataType $data_type) {
        $study_data_type = new StudyDataType();
        $study_data_type->data_type_id = $data_type->id;
        $study_data_type->study_id = $study->id;

        $study_data_type->save();
        return StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->with('study')->first();
    }

    public function delete_study_data_type(Request $request, Study $study, DataType $data_type) {
        $study_data_type = StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->first();
        $study_data_type->delete();
        return 1;
    }
    //END Study Data Types Methods

    // START Study Permissions Methods
    public function set_study_permissions(Request $request, Study $study, User $user) {
        $request->validate([
            'study_permissions' => 'array',
        ]);
        StudyPermission::where('study_id',$study->id)->where('user_id',$user->id)->delete();
        foreach($request->study_permissions as $study_permission) {
            $study_permission = new StudyPermission([
                'study_id' => $study->id,
                'user_id' => $user->id,
                'study_permission' => $study_permission
            ]);
            $study_permission->save();
        }
        return $request->study_permissions;
    }

    public function get_study_permissions(Request $request, Study $study) {
        return $study->study_permission;
    }
    // END Study Permissions Methods
}
