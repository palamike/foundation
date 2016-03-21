<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends SettingController
{
    protected $permissions = [
        'edit' => 'setting_user',
        'update' => 'setting_user',
        'list' => 'setting_user'
    ];
}
