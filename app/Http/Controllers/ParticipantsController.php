<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Permission;
use App\Models\Study;
use App\Models\StudyParticipant;
use App\Models\StudyUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParticipantsController extends Controller
{
    public function get_participants(Request $request, User $user) {
        // Hard coding for now
        $user = Auth::user();

        if($user->can('view_participants','App\Participant')) {
            return Participant::with('studies')->get();
        }
        //If User doesn't have permission to view all participants, then only return participants from studies they can view
        $study_participants = StudyParticipant::whereIn('study_id',$user->user_studies->pluck('study_id'))
            ->select('participant_id')->get()->pluck('participant_id')->toArray();
        return Participant::whereIn('id',$study_participants)->with('studies')->orderBy('studies')->get();
    }

    public function get_participant(Request $request, Participant $participant) {
        return $participant->with('studies');
    }

    public function create_participant(Request $request) {

        try{
            $participant = new Participant($request->all());

            $participant->created_by = Auth::user()->id;
            $participant->updated_by = Auth::user()->id;
            $participant->save();
        }
        catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            // Track for the duplicate entry errors
            if($error_code == 1062){
                return response()->json(['message'=>$request->first_name.' '.$request->last_name.' '.$request->email.' participant already exists.'],500);
            }else{
                return response()->json(['message'=>$e->getMessage()],500);
            }
        }

        // For bulk uploads without studies
        if(count($request->studies)>0){
            foreach ($request->studies as $study){
                if(!is_null($study)){
                    $study_participant = new StudyParticipant([
                        'study_id'=>$study,
                        'participant_id'=> $participant->id
                    ]);
                    $study_participant->save();
                }
            }
        }

        return $participant->with('studies')->where('id',$participant->id)->first();
    }

    public function update_participant(Request $request, Participant $participant) {
        try{
            $participant->updated_by = Auth::user()->id;
            $participant->update($request->all());
            return $participant->with('studies')->where('id',$participant->id)->first();
        }catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            // Track for the duplicate entry errors
            if($error_code == 1062){
                return response()->json(['message'=>$request->first_name.' '.$request->last_name.' '.$request->email.' participant already exists.'],500);
            }else{
                return response()->json(['message'=>$e->getMessage()],500);
            }
        }
    }

    public function delete_participant(Request $request, Participant $participant) {
        $participant->delete();
        return 1;
    }

    /* START Study Participant Methods */
    public function get_participant_studies(Request $request, Participant $participant) {
        // Hard coding for now
        $user = Auth::user();

        if($user->can('view_studies','App\Study') || $user->can('manage_studies','App\Study')) {
            return Study::whereHas('study_participants',function($q) use ($participant) {
                $q->where('participant_id',$participant->id);
            })->with('users')->get();
        }

//         If User doesn't have permission to view all of this participants' study relationships, then only return the studies they can view
        return Study::whereIn('id',$user->user_studies->pluck('study_id'))->whereHas('study_participants',function($q) use ($participant) {
            $q->where('participant_id',$participant->id);
        })->with('users')->get();
    }

    public function add_participant_study(Request $request, Participant $participant, Study $study) {
        $user = Auth::user();

        $study_participant = new StudyParticipant();
        $study_participant->participant_id = $participant->id;
        $study_participant->study_id = $study->id;
        $study_participant->save();

        return Study::where('id',$study_participant->study_id)->
        whereIn('id',$user->user_studies->pluck('study_id'))->whereHas('study_participants',function($q) use ($participant) {
            $q->where('participant_id',$participant->id);
        })->with('users')->first();
    }

    public function remove_participant_study(Request $request, Participant $participant, Study $study) {
        StudyParticipant::where('study_id',$study->id)->where('participant_id',$participant->id)->delete();
        return 1;
    }
    /* END Study Participant Methods */
}
