<?php

namespace App\Http\Controllers;

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

}
