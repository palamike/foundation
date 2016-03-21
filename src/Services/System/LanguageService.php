<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/16/2016 AD
 * Time: 7:40 PM
 */

namespace Palamike\Foundation\Services\System;


class LanguageService
{
    public function getLangArrayJS($domain,$file,$commonFile = 'common.php'){

        $locale = app_locale();
        $lang = [$locale => []];

        $commons = include(resource_path('lang'.DIRECTORY_SEPARATOR.app_locale().DIRECTORY_SEPARATOR.$commonFile));
        foreach($commons as $key => $value){
            $lang[$locale]['common'][str_replace('.','_',$key)] = $value;
        }//foreach

        $lines = include(resource_path('lang'.DIRECTORY_SEPARATOR.app_locale().DIRECTORY_SEPARATOR.$file));
        foreach($lines as $key => $value){
            $lang[$locale][$domain][str_replace('.','_',$key)] = $value;
        }//foreach

        return $lang;
    }

}