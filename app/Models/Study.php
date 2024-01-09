<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    protected $fillable = ['pi_user_id','title','location','description','start_date','end_date','created_by','updated_by'];
    protected $casts = ['created_at'=>'date:Y-m-d','updated_at'=>'date:Y-m-d','start_date'=> 'date:Y-m-d','end_date'=>'date:Y-m-d'];

    public function study_participants(){
        return $this->hasMany(StudyParticipant::class,'study_id');
    }

    public function participants() {
        return $this->belongsToMany(Participant::class,'study_participants');
    }

    public function pi(){
        return $this->belongsTo(User::class,'id');
    }

    public function permissions(){
        return $this->hasMany(StudyPermission::class,'study_permissions');
    }
}
