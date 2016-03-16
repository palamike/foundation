<?php

namespace Palamike\Foundation\Models\System;

use Palamike\Foundation\Models\Common\CommonScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CommonScopeTrait;

    protected $fillable = [
        'name','label','description','input','default','value',
        'choices','validation','config','group_id',
        'created_by','updated_by','created_by_name','updated_by_name'
    ];

    protected $casts = [
        'config' => 'array'
    ];

    /**
     * Relationship between setting group and setting
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function settingGroup(){
        return $this->belongsTo(SettingGroup::class,'group_id');
    }
}
