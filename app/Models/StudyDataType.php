<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyDataType extends Model
{
    use HasFactory;

    protected $fillable = ['data_type_id','study_id'];

    public function study() {
        return $this->belongsTo(Study::class);
    }

    public function data_type() {
        return $this->belongsTo(DataType::class);
    }
}