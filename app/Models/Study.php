<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $casts = [];

    public function study_participants(){
        return $this->hasMany(StudyParticipant::class,'study_id');
    }

    public function participants() {
        return $this->belongsToMany(Participants::class,'study_participants');
    }
}