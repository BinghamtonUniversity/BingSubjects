<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyDataType extends Model
{
    use HasFactory;

    protected $fillable = ['study_id','data_type_id','description'];

    public function study() {
        return $this->belongsTo(Study::class,'study_id');
    }

    public function data_type() {
        return $this->belongsTo(DataType::class,'data_type_id');
    }
}
