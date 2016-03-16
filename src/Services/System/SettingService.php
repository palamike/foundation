<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/10/2016 AD
 * Time: 11:05 AM
 */

namespace Palamike\Foundation\Services\System;

use Palamike\Foundation\Models\System\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private $settings = [];

    public function __construct()
    {
        $this->settings = Cache::rememberForever($this->getCacheKey(),function() {
            $results = Setting::all();
            $arr = [];
            foreach($results as $result){
                $arr[$result->name] = $result;
            }//foreach

            return $arr;
        });
    }

    public function getObject($name){
        return !empty($this->settings[$name]) ? $this->settings[$name] : null;
    }//public function getObject

    public function getValue($name){

        if(empty($this->settings[$name])){
            return 'undefined';
        }//if

        switch($this->settings[$name]->type){
            case 'boolean' :
                return (boolean) $this->settings[$name]->value;
            case 'string' :
                return (string) $this->settings[$name]->value;
            case 'integer' :
                return (integer) $this->settings[$name]->value;
            case 'double' :
                return (double) $this->settings[$name]->value;
            default :
                return (string) $this->settings[$name]->value;
        }//switch

    }//public function getValue

    public function getCacheKey(){
        return 'application-settings';
    }

    public function clearCache(){
        Cache::forget($this->getCacheKey());
    }
}