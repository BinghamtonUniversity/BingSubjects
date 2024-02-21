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
        'password',
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

    public function studies() {
        return $this->belongsToMany(Study::class,'study_viewers')->orderBy('order');
    }

    public function user_permissions() {
        return $this->hasMany(Permission::class,'user_id');
    }

    public function study_permissions() {
        return $this->hasMany(StudyPermission::class,'user_id');
    }

    public function is_study_manager($study_id=null) {
        if (is_null($study_id)){
            return (bool)StudyPermission::where('user_id',1)
                ->whereIn('study_permission',['manage_study'])
                ->first();
        }
        return (bool)StudyPermission::where('user_id',1)
            ->where('study_id',$study_id)
            ->whereIn('study_permission',['manage_study'])
            ->first();
    }

    public function is_study_viewer($study_id=null) {
        if (is_null($study_id)){
            return (bool)StudyPermission::where('user_id',1)
                ->whereIn('study_permission',['view_study'])
                ->first();
        }
        return (bool)StudyPermission::where('user_id',1)
            ->where('study_id',$study_id)
            ->whereIn('study_permission',['view_study'])
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
