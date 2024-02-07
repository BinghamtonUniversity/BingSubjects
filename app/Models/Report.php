<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['name','description','report','owner_user_id','permissions'];
    protected $casts = ['report' => 'object','permissions'=>'object'];
    protected $with = ['owner'];

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    public function owner(){
        return $this->belongsTo(User::class,'owner_user_id');
    }
    public function getPermissionsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        return json_decode($value);
    }
}
