<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyDataType extends Model
{
    use HasFactory;

    protected $fillable = ['study_id','data_type_id','description'];
    protected $with = ['data_type'];
    public function study() {
        return $this->belongsTo(Study::class,'study_id');
    }

    public function data_type() {
        return $this->belongsTo(DataType::class,'data_type_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
