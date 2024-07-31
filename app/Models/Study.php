<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    protected $fillable = ['pi_user_id','title','description','start_date','end_date','location','design','sample_type','created_by','updated_by'];
    protected $casts = ['created_at'=>'date:Y-m-d','updated_at'=>'date:Y-m-d','start_date'=> 'date:Y-m-d','end_date'=>'date:Y-m-d'];
    protected $with = ['pi','study_data_types'];
    protected $appends = ['participantCount'];

    public function pi() {
        return $this->belongsTo(User::class,'pi_user_id');
    }

    public function study_users() {
        return $this->hasMany(StudyUser::class,'study_id');
    }

    public function users() {
        return $this->belongsToMany(User::class,'study_users')->withPivot('type');
    }

    public function study_participants() {
        return $this->hasMany(StudyParticipant::class,'study_id');
    }

    public function participants() {
        return $this->belongsToMany(Participant::class,'study_participants');
    }

    public function study_data_types() {
        return $this->hasMany(StudyDataType::class,'study_id');
    }

    public function data_types() {
        return $this->belongsToMany(DataType::class,'study_data_types')->withPivot('id','description');
    }

    public function getParticipantCountAttribute() {
        $participants = $this->participants;
        return $participants->count();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
