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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudiesController extends Controller
{
    public function list_studies(Request $request) {
        $user = Auth::user();
        // Check for the global permissions
        if(!is_null(Permission::where('user_id',$user->id)
            ->where(function($q){
                $q->where('permission','view_studies')
                    ->orWhere('permission','manage_studies');
            })->first())) {
            return Study::get();
        }

        // Check for the study permissions
        if ($user->is_study_user()){
            $user_studies = StudyUser::where('user_id',$user->id)->get()->toArray();
            return Study::whereIn('id',array_values(array_column($user_studies,'study_id')))->get();
        }
    }


    public function create_study(Request $request) {
        $study = new Study($request->all());
        // Hard coding these values for now until we have authentication and users set up properly.

        $study->save();

        // Make PI a study manager
        $study_user = new StudyUser;
        $study_user->study_id = $study->id;
        $study_user->user_id = $study->pi_user_id;
        $study_user->type = "Manager";
        $study_user->save();

        return $study;
    }

    public function update_study(Request $request, Study $study) {
        $study->updated_by = Auth::user()->id;

        $study->update($request->all());
        return $study;
    }

    public function delete_study(Request $request, Study $study) {
        $study->delete();
        return 1;
    }

    /* START Study Participant Methods */
    public function get_study_participants(Request $request, Study $study) {
        return StudyParticipant::where('study_id',$study->id)->get();
    }

    public function add_study_participant(Request $request, Study $study, Participant $participant) {
        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;
        $study_participant->save();

        return $study_participant;
    }

    public function remove_study_participant(Request $request, Study $study, Participant $participant) {
        return StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->delete();
    }
    /* END Study Participant Methods */

    public function add_study_data_type(Request $request, Study $study, DataType $data_type) {
        $study_data_type = new StudyDataType($request->all());
        $study_data_type->study_id = $study->id;
        $study_data_type->data_type_id = $data_type->id;
        $study_data_type->save();

        $study_data_type->data_type;

        return $study_data_type;
    }

    public function update_study_data_type(Request $request, Study $study, StudyDataType $study_data_type) {
        $study_data_type->update($request->all());

        return $study_data_type;
    }

    public function remove_study_data_type(Request $request, Study $study, StudyDataType $study_data_type) {
        return $study_data_type->delete();

    }
    /* END Study Data Type Methods */

    /* START Study User Methods */
    public function get_study(Request $request, Study $study) {;
        $study->users;
        $study->study_data_types;

        return $study;
    }

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
        try{
            $study_user = new StudyUser($request->all());
            $study_user->study_id = $study->id;
            $study_user->user_id = $user->id;
            $study_user->save();
            $found_user = User::select('id', 'first_name', 'last_name', 'email')->whereHas('user_studies', function ($q) use ($study) {
                $q->where('study_id', $study->id);
            })->where('id', $user->id)->first()->toArray();
            $found_user['pivot']['type'] = $user->user_study_type($study->id);

            return $found_user;
        }catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            // Track for the duplicate entry errors
            if($error_code == 1062){
                return response()->json(['message'=>'User can only have one permission in a study.'],500);
            }else{
                return response()->json(['message'=>$e->getMessage()],500);
            }
        }
    }

    public function update_study_user(Request $request, Study $study, User $user) {
        try{

            DB::table('study_users')->upsert([
                'study_id'=>$study->id,
                'user_id'=>$user->id,
                'type'=>$request->type
            ],['study_id','user_id']);

            $found_user = User::select('id', 'first_name', 'last_name', 'email')->whereHas('user_studies', function ($q) use ($study) {
                $q->where('study_id', $study->id);
            })->where('id', $user->id)->first()->toArray();
            $found_user['pivot']['type'] = $user->user_study_type($study->id);

            return $found_user;
        }catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            // Track for the duplicate entry errors
            if($error_code == 1062){
                return response()->json(['message'=>'User can only have one permission in a study.'],500);
            }else{
                return response()->json(['message'=>$e->getMessage()],500);
            }
        }
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

        $manageable_studies = StudyUser::where('user_id',1)->where('type','manager')->select('study_id')->get()->pluck('study_id')->toArray();

        if($user->can('manage_studies','App\Participant')) {
            return Study::get();
        }
        if(!empty($manageable_studies)) {
            return Study::whereIn('id',$manageable_studies)->get();
        }
        else {
            return 0;
        }
    }



}
