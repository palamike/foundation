<?php

namespace Palamike\Foundation\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class SignonSession extends Model
{
    protected $fillable = ['user_id','session_id'];

    public function scopeUser($query,$userId){
        return $query->where('user_id','=',$userId);
    }

}
