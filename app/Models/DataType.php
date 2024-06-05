<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
    protected $fillable = ['category','type'];

    public function data_type_studies() {
        return $this->hasMany(StudyDataType::class,'data_type_id');
    }

    public function studies() {
        return $this->belongsToMany(Study::class,'data_type_studies');
    }

    public function data_type_study_description($study_id) {
        return StudyDataType::where('data_type_id',$this->id)->where('study_id',$study_id)->select('description')->get()->pluck('description')->first();
    }
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
