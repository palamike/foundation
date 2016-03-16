<?php

namespace Palamike\Foundation\Models\Auth;

use App\Models\Media\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name','email','password','status', 'avatar_id' ,'remember_token','created_by','updated_by','created_by_name','updated_by_name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param $roles string|Collection
     * @return bool
     */
    public function hasRole($roles){
        if(is_string($roles)){
            return $this->roles->contains('name',$roles);
        }//if

        return !! $roles->intersect($this->roles)->count();
    }//public function hasRole

    public function avatar(){
        return $this->hasOne(Media::class,'id','avatar_id');
    }
}
