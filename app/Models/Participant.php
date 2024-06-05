<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['first_name','last_name','date_of_birth','sex','race','city_of_birth','email','phone_number', 'participant_comments'];
    protected $casts = ['date_of_birth'=>'date:Y-m-d',
//        'studies'=>'object'
    ];

    public function study_participants() {
        return $this->hasMany(StudyParticipant::class,'participant_id');
    }

    public function studies() {
        return $this->belongsToMany(Study::class,'study_participants');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }


}
