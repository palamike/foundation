<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GeneralController extends SettingController
{
    protected $permissions = [
        'edit' => 'setting_general',
        'update' => 'setting_general',
        'list' => 'setting_general'
    ];
}
