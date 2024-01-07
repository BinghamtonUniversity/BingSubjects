<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['first_name','last_name','date_of_birth','sex','race','city_of_birth','email','phone_number'];
    protected $casts = ['date_of_birth'=>'date:Y-m-d'];

    public function study_participants(){
        return $this->hasMany(StudyParticipant::class,'participant_id');
    }

    public function studies() {
        return $this->belongsToMany(Study::class,'study_participants');
    }
}
