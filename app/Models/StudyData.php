<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyData extends Model
{
    use HasFactory;

    protected $fillable = ['study_id','type', 'description'];

    public function study() {
        return $this->belongsTo(Study::class); // Many?
    }
}