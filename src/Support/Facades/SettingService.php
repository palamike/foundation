<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/10/2016 AD
 * Time: 11:35 AM
 */

namespace Palamike\Foundation\Support\Facades;


use Illuminate\Support\Facades\Facade;

class SettingService extends Facade
{
    protected static function getFacadeAccessor() { return 'foundation.settings'; }
}