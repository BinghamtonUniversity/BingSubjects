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
    public function get_studies(Request $request, User $user) {
        // Hard coding for now
        $user = User::find(1);

        $permission = Permission::where('user_id',1)->select('permission')->get()->pluck('permission');
        if($permission->contains('view_studies_info') || $permission->contains('view_studies') || $permission->contains('manage_studies')) {
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
        // Hard coding for now
        $user = User::find(1);

        $permission = Permission::where('user_id',1)->select('permission')->get()->pluck('permission');
        if($user->is_study_user() ||
            $permission->contains('view_studies') || $permission->contains('manage_studies')) {
            return StudyParticipant::where('study_id',$study->id)->with('participant')->get();
        }
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
        return DataType::where('study_id',$study->id)->get(); //->with('data_type')->get();
        // return StudyDataType::where('study_id',$study->id)->with('data_type')->get();
    }

    // public function add_study_data_type(Request $request, Study $study, DataType $data_type) {
    //     $study_data_type = new StudyDataType();
    //     $study_data_type->data_type_id = $data_type->id;
    //     $study_data_type->study_id = $study->id;

    //     $study_data_type->save();
    //     return StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->with('study')->first();
    // }

    // public function delete_study_data_type(Request $request, Study $study, DataType $data_type) {
    //     $study_data_type = StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->first();
    //     $study_data_type->delete();
    //     return 1;
    // }
    //END Study Data Types Methods


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

    // START Study User Methods
    public function get_study_users(Request $request, Study $study) {
        $users = User::select('id','first_name','last_name','email')->whereHas('user_studies',function($q) use ($study) {
            $q->where('study_id',$study->id);
        })->orderBy('last_name','asc')->orderBy('first_name','asc')->get();
        foreach($users as $user) {
            $user->type = $user->user_study_type($study->id);
        }
        return $users;
    }

    // public function get_study_viewers(Request $request, Study $study) {
    //     $viewers = User::select('id','first_name','last_name')->whereHas('user_studies',function($q) use ($study) {
    //         $q->where('study_id',$study->id)
    //         ->where('type','viewer');
    //     })->orderBy('last_name','asc')->orderBy('first_name','asc')->get();
    //     $viewers_modified = [];
    //     foreach($viewers as $viewer) {
    //         $viewers_modified[] = [
    //             'id' => $viewer->id,
    //             'name' => $viewer->first_name.' '.$viewer->last_name,
    //         ];
    //     }   
    //     return $viewers_modified;
    // }

    // public function get_study_managers(Request $request, Study $study) {
    //     $managers = User::select('id','first_name','last_name')->whereHas('user_studies',function($q) use ($study) {
    //         $q->where('study_id',$study->id)
    //         ->where('type','manager');
    //     })->orderBy('last_name','asc')->orderBy('first_name','asc')->get();
    //     $managers_modified = [];
    //     foreach($managers as $manager) {
    //         $managers_modified[] = [
    //             'id' => $manager->id,
    //             'name' => $manager->first_name.' '.$manager->last_name,
    //         ];
    //     }   
    //     return $managers_modified;
    // }

    public function add_study_user(Request $request, Study $study, User $user) {
        $study_user = new StudyUser($request->all());
        $study_user->study_id = $study->id;
        $study_user->user_id = $user->id;
        $study_user->save();
        return StudyUser::where('study_id',$study->id)->where('user_id',$user->id)->first(); //->with('type')
    }

    public function update_study_user(Request $request, Study $study, User $user) {
        $study_user = StudyUser::where('study_id',$study->id)->where('user_id',$user->id)->first();
        $study_user->update($request->all());
        return $study_user;
    }

    public function remove_study_user (Study $study, User $user) {
        $study_user = StudyUser::where('study_id',$study->id)->where('user_id',$user->id)->first();
        $study_user->delete();
        return 1;
    }
}