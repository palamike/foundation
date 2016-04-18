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

    /**
     * Get the array from the language files for javascript i18n format
     * It will produce common domain automatically
     *
     * @param $file string file name
     * @param null $basePath string base path with in the resource directory 
     * for example 'lang/vendor/foundation'.it should be a path before 'en' directory
     * @return array which as structure...
     * 
     *  [
     *              'language-key1' => 'translation-1'
     *              'language-key2' => 'translation-2'
     *  ]
     * 
     * @throws \Exception
     */
    public function getLangArrayJS($file,$basePath = null){

        $locale = app_locale();

        $lang = [];

        if(empty($basePath)){
            $basePath = 'lang'.DIRECTORY_SEPARATOR.$locale.DIRECTORY_SEPARATOR;
        }//if
        else{
            $basePath = 'lang'.DIRECTORY_SEPARATOR.finish_path($basePath).$locale.DIRECTORY_SEPARATOR;
        }//else

        $lineFilePath = resource_path($basePath.$file);
        
        if(file_exists($lineFilePath)){
            $lines = include($lineFilePath);
            foreach($lines as $key => $value){
                $lang[str_replace('.','_',$key)] = $value;
            }//foreach    
        }
        else{
            throw new \Exception('File not found : '.$lineFilePath);
        }

        return $lang;
    }
    
    public function getAvaliableLanguages(){
        return ['en','th'];
    }

}