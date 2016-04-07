<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/16/2016 AD
 * Time: 7:39 PM
 */

namespace Palamike\Foundation\Methods;


use Illuminate\Support\Facades\Cache;
use Palamike\Foundation\Support\Facades\MenuService as Menu;

class CommonCacheTrait {

    public function clearMenuCache($menu = 'menu'){
        $key = Menu::getCacheKey($menu);
        Cache::forget($key);
    }

}