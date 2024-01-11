<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['study_id','participant_id'];

    public function study(){
        return $this->belongsTo(Study::class);
    }

    public function participant(){
        return $this->belongsTo(Participant::class);
    }

}
