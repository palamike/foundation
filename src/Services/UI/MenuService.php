<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/12/2016 AD
 * Time: 9:24 AM
 */

namespace App\Services\UI;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use Illuminate\Support\Facades\Cache;

class MenuService
{

    public function getCacheKey($menu){
        return 'application-menu-'.app_locale().'-'.$menu;
    }

    public function getArray($menu){
        $cache = Cache::rememberForever($this->getCacheKey($menu),function () use ($menu){

            $file = resource_path('menu'.DIRECTORY_SEPARATOR.$menu.'.yaml');
            $yaml = new Parser();

            try{
                $value = $yaml->parse(file_get_contents($file));
                $result = [];

                foreach($value['menus'] as $menu){
                    array_push($result,$this->processCallback($menu));
                }//foreach


                return $result;
            }
            catch(ParseException $e){
                Log::error('Can not parse menu file : '.$file);
                throw $e;
            }
        });

        return $cache;

    }

    public function processCallback($menu){
        if(array_key_exists('children',$menu)){

            $childResult = [];

            foreach($menu['children'] as $child){
                $child = $this->processCallback($child);
                array_push($childResult,$child);
            }

            $menu['children'] = $childResult;
        }//if

        if(array_key_exists('callback',$menu)){
            if(function_exists($menu['callback'])){
                $arr = call_user_func($menu['callback']);

                if(!array_key_exists('children',$menu)){
                    $menu['children'] = [];
                }//if

                if((sizeof($arr) > 0) && !array_key_exists('label',$arr)){
                    foreach($arr as $ar){
                        array_push($menu['children'],$ar);
                    }
                }
                else{
                    array_push($menu['children'],$arr);
                }


            }
        }//if

        return $menu;
    }
}