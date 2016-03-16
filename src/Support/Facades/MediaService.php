<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/24/2016 AD
 * Time: 5:25 PM
 */

namespace Palamike\Foundation\Support\Facades;


use Illuminate\Support\Facades\Facade;

class MediaService extends Facade
{
    protected static function getFacadeAccessor() { return 'foundation.media'; }
}