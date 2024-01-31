<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyPermission extends Model
{
    protected $fillable = ['study_id','user_id','study_permission'];

    public function study(){
        return $this->belongsTo(Study::class,'study_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
