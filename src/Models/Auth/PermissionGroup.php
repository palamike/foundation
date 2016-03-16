<?php

namespace Palamike\Foundation\Models\Auth;

use Palamike\Foundation\Models\Common\CommonScopeTrait;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{

    use CommonScopeTrait;

    protected $fillable = ['name','label','created_by','updated_by','created_by_name','updated_by_name'];

    /**
     * The permission group has many permissions.
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class,'group_id');
    }
}
