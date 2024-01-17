<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
    use HasFactory;

    protected $fillable = ['type','description'];

    public function study_data_types() {
        return $this->hasMany(StudyDataType::class,'data_type_id');
    }

    public function studies() {
        return $this->belongsToMany(Study::class,'study_data_types'); 
    }
}
