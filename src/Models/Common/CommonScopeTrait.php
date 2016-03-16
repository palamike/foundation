<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/10/2016 AD
 * Time: 10:20 AM
 */

namespace Palamike\Foundation\Models\Common;


trait CommonScopeTrait
{
    public function scopeByName($query,$name){
        return $query->where('name','=',$name);
    }

    public function scopeByCode($query,$code){
        return $query->where('code','=',$code);
    }
}