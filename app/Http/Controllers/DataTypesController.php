<?php

namespace App\Http\Controllers;

use App\Models\Study;
use App\Models\DataType;
use App\Models\StudyDataType;
use Illuminate\Http\Request;

class DataTypesController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(DataTypes $dataTypes)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(DataTypes $dataTypes)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, DataTypes $dataTypes)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(DataTypes $dataTypes)
    // {
    //     //
    // }



    // Look into moving this to template above
    public function get_data_types(Request $request) {
        return DataType::all();
    }

    public function get_data_type(Request $request, DataType $data_type) {
        return $data_type;
    }

    public function create_data_type(Request $request) {
        $data_type = new DataType($request->all());
        // Hard coding these values for now until we have authentication and users set up properly.
        $data_type->created_by = 1;
        $data_type->updated_by = 1;
        $data_type->save();
        return $data_type;
    }

    public function delete_data_type(Request $request, DataType $data_type) {
        $data_type->delete();
        return 1;
    }

    public function update_data_type(Request $request, DataType $data_type) {
        $data_type->updated_by = 1;
        $data_type->update($request->all());
        return $data_type;
    }


    //START Study Data Types Methods
    public function get_data_type_studies(Request $request, Study $study) {
        return StudyDataType::with('data_type')->get();
    }

    public function add_data_type_study(Request $request, DataType $data_type, Study $study){
        $study_data_type = new StudyDataType();
        $study_data_type->data_type_id = $data_type->id;
        $study_data_type->study_id = $study->id;

        $study_data_type->save();
        return StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->with('study')->first();
    }

    public function delete_data_type_study(Request $request, DataType $data_type, Study $study){
        $study_data_type = StudyDataType::where('study_id',$study->id)->where('data_type_id',$data_type->id)->first();
        $study_data_type->delete();
        return 1;
    }
    //END Study Data Types Methods
}