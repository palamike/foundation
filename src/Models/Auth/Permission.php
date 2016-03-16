<?php

namespace Palamike\Foundation\Models\Auth;

use Palamike\Foundation\Models\Common\CommonScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use CommonScopeTrait;

    protected $fillable = ['name','label','group_id','created_by','updated_by','created_by_name','updated_by_name'];

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Relationship between permission group and permission
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permissionGroup(){
        return $this->belongsTo(PermissionGroup::class,'group_id');
    }
}
