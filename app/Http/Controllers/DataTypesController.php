<?php

namespace App\Http\Controllers;

use App\Models\Study;
use App\Models\DataType;
use App\Models\StudyDataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataTypesController extends Controller
{
    public function list_data_types(Request $request) {
        return DataType::get();
    }

    public function create_data_type(Request $request) {
        $data_type = new DataType($request->all());
        // Hard coding these values for now until we have authentication and users set up properly.
        $data_type->created_by = Auth::user()->id;
        $data_type->updated_by = Auth::user()->id;
        $data_type->save();
        return $data_type;
    }

    public function update_data_type(Request $request, DataType $data_type) {
        $data_type->updated_by = Auth::user()->id;
        $data_type->update($request->all());
        return $data_type;
    }

    public function delete_data_type(Request $request, DataType $data_type) {
        $data_type->delete();
        return 1;
    }
}
