<?php

namespace Palamike\Foundation\Models\Auth;

use Palamike\Foundation\Models\Common\CommonScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    use CommonScopeTrait;

    protected $fillable = ['name','label','description','redirect','created_by','updated_by','created_by_name','updated_by_name'];

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
