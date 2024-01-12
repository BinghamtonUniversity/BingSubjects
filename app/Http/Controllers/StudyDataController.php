<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Study;
use App\Models\StudyData;

class StudyDataController extends Controller
{
    public function get_study_data(Request $request) {  // naming convention for plural vs singular data? 
        return StudyData::all();
    }

    // // Search for all entries of a study data type
    // public function get_study_datum(Request $request, StudyData $study-data) { // study_datum for singular?
    //     return $study_data;
    // }

    // With Study ID
    public function create_study_data(Request $request, Study $study) {
        $study_data = new StudyData($request->all());
        $study_data->study_id = $study->id;

        $study_data->save();
        return $study_data;
    }

    // Revisiting the structure of this
    // public function delete_study_data(Request $request, StudyData $study_data) {
    //     $study_data->delete();
    //     return 1;
    // }

    // public function update_study_data(Request $request, StudyData $study_data) {
    //     $study_data->update($request->all());
    //     return 1;
    // }
}