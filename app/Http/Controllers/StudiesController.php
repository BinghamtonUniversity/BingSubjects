<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataType;
use App\Models\Participant;
use App\Models\Permission;
use App\Models\Study;
use App\Models\StudyDataType;
use App\Models\StudyParticipant;
use App\Models\StudyUser;
use App\Models\User;

class StudiesController extends Controller
{
    public function list_studies(Request $request, User $user) {
        // Hard coding for now
        $user = User::find(1);

        if($user->can('view_studies','App\Study')) {
            return Study::get();
        }
        // If User doesn't have permission to view all studies, then only return studies they can view
        return Study::whereIn('id',$user->user_studies->pluck('study_id'))->get();
    }

    public function get_study(Request $request, Study $study) {
        $study = Study::where('id',$study->id)->with('users')->with('data_types')->with('participants')->first();
        return $study;
    }

    public function create_study(Request $request) {
        $study = new Study($request->all());
        // Hard coding these values for now until we have authentication and users set up properly.
        $study->created_by = 1;
        $study->updated_by = 1;
        $study->save();
        return $study; //->first();
    }

    public function update_study(Request $request, Study $study) {
        $study->updated_by = 1;
        $study->update($request->all());
        return $study;
    }

    public function delete_study(Request $request, Study $study) {
        $study->delete();
        return 1;
    }

    /* START Study Participant Methods */
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

    public function remove_study_participant(Request $request, Study $study, Participant $participant) {
        $study_participant = StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->first();
        $study_participant->delete();
        return 1;
    }
    /* END Study Participant Methods */


    /* START Study Data Type Methods */
    public function get_study_data_types(Request $request, Study $study) {
        $data_types = DataType::select('id','category','type')->whereHas('data_type_studies',function($q) use ($study) {
            $q->where('study_id',$study->id);
        })->get();
        foreach($data_types as $data_type) {
            $data_type->description = $data_type->data_type_study_description($study->id);
        }
        return $data_types;
    }

    public function add_study_data_type(Request $request, Study $study, DataType $data_type) {
        $study_data_type = new StudyDataType($request->all());
        $study_data_type->study_id = $study->id;
        $study_data_type->data_type_id = $data_type->id;
        $study_data_type->save();
        return StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->first(); //->with('study')
    }

    public function update_study_data_type(Request $request, Study $study, DataType $data_type) {
        $study_data_type = StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->first();
        $study_data_type->update($request->all()); //only description?
        return $study_data_type;
    }

    public function remove_study_data_type(Study $study, DataType $data_type) {
        $study_data_type = StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->first();
        $study_data_type->delete();
        return 1;
    }
    /* END Study Data Type Methods */

    /* START Study User Methods */
    public function get_study_users(Request $request, Study $study) {
        $users = User::select('id','first_name','last_name','email')->whereHas('user_studies',function($q) use ($study) {
            $q->where('study_id',$study->id);
        })->orderBy('last_name','asc')->orderBy('first_name','asc')->get();
        foreach($users as $user) {
            $user->type = $user->user_study_type($study->id);
        }
        return $users;
    }

    public function add_study_user(Request $request, Study $study, User $user) {
        $study_user = new StudyUser($request->all());
        $study_user->study_id = $study->id;
        $study_user->user_id = $user->id;
        $study_user->save();
        return StudyUser::where('study_id',$study->id)->where('user_id',$user->id)->first(); //->with('type')
    }

    public function update_study_user(Request $request, Study $study, User $user) {
        $study_user = StudyUser::where('study_id',$study->id)->where('user_id',$user->id)->first();
        $study_user->update($request->all()); //only type?
        return $study_user;
    }

    public function remove_study_user(Study $study, User $user) {
        $study_user = StudyUser::where('study_id',$study->id)->where('user_id',$user->id)->first();
        $study_user->delete();
        return 1;
    }
    /* END Study User Methods */

    /* Helper function for dropdown options */
    public function get_manageable_studies(Request $request, User $user) {
        // Hard coding for now
        $user = User::find(1);

        $permission = Permission::where('user_id',1)->select('permission')->get()->pluck('permission');
        $manageable_studies = StudyUser::where('user_id',1)->where('type','manager')->select('study_id')->get()->pluck('study_id')->toArray();
        
        if($permission->contains('manage_studies')) {
            return Study::get();
        }
        if (!empty($manageable_studies)) {
            return Study::whereIn('id',$manageable_studies)->get();
        }
        else {
            return 0;
        }
    }
}