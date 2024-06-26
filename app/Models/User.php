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
        'bnumber',
        'active',
        'will_expire',
        'expiration_date'
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
            return !is_null(StudyUser::where('user_id',$this->id)
                ->where('type','manager')
                ->first());
        }
        return !is_null(StudyUser::where('user_id',$this->id)
            ->where('study_id',$study_id)
            ->where('type','manager')
            ->first());
    }

    public function is_report_user($report_id=null){
        if (is_null($report_id)){
             return !is_null(Report::whereJsonContains('permissions',$this->id)
                    ->orWhere('owner_user_id',$this->id)
                ->first());
        }
        return !is_null(Report::where('report_id',$report_id->id)
            ->where(function($q){
                $q->whereJsonContains('permissions',$this->id)
                    ->orWhere('owner',$this->id);
            })
            ->first());
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
