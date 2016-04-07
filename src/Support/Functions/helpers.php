<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/10/2016 AD
 * Time: 10:59 AM
 */

use Palamike\Foundation\Support\Facades\SettingService;
use Palamike\Foundation\Support\Facades\AssetService;
use Palamike\Foundation\Support\Facades\MenuService;
use Palamike\Foundation\Support\Facades\LanguageService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

function setting($name){
    return SettingService::getValue($name);
}//function setting

function available_languages(){
    return ['en','th'];
}

function scripts($scripts){
    return AssetService::scripts($scripts);
}//function scripts

function styles($styles){
    return AssetService::styles($styles);
}//function styles

function partial_bold($input,$position){
    return "<b>".substr($input,0,(integer)$position)."</b>".substr($input,(integer)$position);
}//function acronyme

function accronyme(){
    $accronyme = setting('application.accronyme');
    return partial_bold($accronyme,1);
}

function app_name_decorated(){
    $appName = setting('application.name');
    $appNameBold = setting('application.name.bold');
    return partial_bold($appName,$appNameBold);
}

function app_locale(){
    return App::getLocale();
}//function app_locale

function menu($menu){
    return MenuService::getArray($menu);
}

function user(){
    return Auth::user();
}

function avatar(){
    $avatar =  user()->avatar;
    if(!empty($avatar)){
        return url($avatar->web_path);
    }
    else{
        return url('uploads/media/9999-99-99/no-avatar.png');
    }
}

function role(){
    return Auth::user()->roles->first();
}

function breadcrumb($icon,$items){
    return view('layouts.partials.breadcrumb',compact('icon','items'))->render();
}

function get_lang_array($domain,$file){
    return json_encode(LanguageService::getLangArrayJS($domain,$file));
}

function query_replace_dash($text){
    return str_replace('__','.',$text);
}

/**
 * @param $permissions array of permission text
 * @param bool $some if find some permission allow then allow
 * @return bool
 */
function has_permissions($permissions,$some = true){

    $result = false;

    foreach($permissions as $perm){
        if(Gate::allows($perm)){
            $result = true;
            if($result && $some){
                return $result;
            }//if some then return when found true;
        }
        else{
            $result = false;
        }
    }

    return $result;

}//function

function dot_dash($str){
    return str_replace('.','_',$str);
}

function dash_dot($str){
    return str_replace('_','.',$str);
}

/**
 *
 * Map the object arrays with given key field in object
 * @param $key string key field name
 * @param $objects array
 * @param $numericAware boolean
 *
 * @return array
 */
function object_array_map($key,$objects,$numericAware = true){
    $map = [];
    foreach($objects as $object){
        if($numericAware && is_numeric($object->{$key})){
            $map['_'.$object->{$key}] = $object;
        }//if
        else{
            $map[$object->{$key}] = $object;
        }
    }//foreach

    return $map;
}

/**
 *
 * Transform the length aware paginator object to array and put custom data field
 *
 * @param $paginate \Illuminate\Contracts\Pagination\LengthAwarePaginator  length aware paginator object
 * @param $data array data field for pagination
 * @return array
 */
function transform_paginator($paginate,$data){
    $transform = [
        'total' => $paginate->total(),
        'per_page' => $paginate->perPage(),
        'current_page' => $paginate->currentPage(),
        'last_page' => $paginate->lastPage(),
        'next_page_url' => $paginate->nextPageUrl(),
        'prev_page_url' => $paginate->previousPageUrl(),
        'data' => $data
    ];

    return $transform;
}

function date_now($format){
    return \Carbon\Carbon::now()->format($format);
}