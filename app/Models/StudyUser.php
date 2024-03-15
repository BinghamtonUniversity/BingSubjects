<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyUser extends Model
{
    protected $fillable = ['study_id','user_id','type'];

    public function study() {
        return $this->belongsTo(Study::class,'study_id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}