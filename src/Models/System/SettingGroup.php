<?php

namespace Palamike\Foundation\Models\System;

use Palamike\Foundation\Models\Common\CommonScopeTrait;
use Illuminate\Database\Eloquent\Model;

class SettingGroup extends Model
{

    use CommonScopeTrait;

    protected $fillable = [
        'name','label','description',
        'created_by','updated_by','created_by_name','updated_by_name'
    ];

    /**
     * The setting group has many settings.
     */
    public function settings()
    {
        return $this->hasMany(Setting::class,'group_id');
    }
}
