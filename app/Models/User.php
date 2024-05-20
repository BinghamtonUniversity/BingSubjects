<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'bnumber'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token','user_permissions'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    protected $appends = ['permissions'];

    public function user_permissions() {
        return $this->hasMany(Permission::class,'user_id');
    }

    public function studies() {
        return $this->belongsToMany(Study::class,'user_studies');
    }

    public function user_studies() {
        return $this->hasMany(StudyUser::class,'user_id');
    }

    public function user_study_type($study_id) {
        return StudyUser::where('user_id',$this->id)->where('study_id',$study_id)->select('type')->get()->pluck('type')->first();
    }

    public function is_study_user($study_id=null) {
        if (is_null($study_id)){
            return !is_null(StudyUser::where('user_id',$this->id)->first());
        }
        return !is_null(StudyUser::where('user_id',$this->id)->where('study_id',$study_id)->first());
    }

    public function is_study_manager($study_id=null) {
        if (is_null($study_id)){
            return (bool)StudyUser::where('user_id',$this->id)
                ->where('type','manager')
                ->first();
        }
        return (bool)StudyUser::where('user_id',$this->id)
            ->where('study_id',$study_id)
            ->where('type','manager')
            ->first();
    }

    public function getPermissionsAttribute() {
        $permissions = $this->user_permissions()->get();
        $permissions_arr = [];
        foreach($permissions as $permission) {
            $permissions_arr[] = $permission->permission;
        }
        return $permissions_arr;
    }
}
