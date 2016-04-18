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

/**
 * Get Setting value by key
 *
 * @param $name string key of the setting
 * @param $default mixed default value if key is not exist
 * @return mixed value of setting
 */
function setting($name,$default = 'undefined'){
    return SettingService::getValue($name,$default);
}//function setting

/**
 * Get Available Languages provided by system
 *
 * @return array available languages in the system
 */
function available_languages(){
    return LanguageService::getAvaliableLanguages();
}

/**
 * Get current application locale
 *
 * @return string get current application locale
 */
function app_locale(){
    return App::getLocale();
}//function app_locale

/**
 * Get json output for the language file use for javascript i18n purpose
 * @param $file string language file name (without path)
 * @param null $basePath string base path with in the resource directory
 * for example 'lang/vendor/foundation'.it should be a path before 'en' directory
 * @return string json string of language file
 */
function get_lang_json($file,$basePath = null){
    return json_encode(LanguageService::getLangArrayJS($file,$basePath));
}

/**
 * Output the script tags. This function can use in blade template
 *
 * @param $scripts string|array script reference name from assets.php config
 * @return string script tags
 */
function scripts($scripts){
    return AssetService::scripts($scripts);
}//function scripts

/**
 * Output the style tags. This function can use in blade template
 *
 * @param $styles string|array style reference name from assets.php config
 * @return string style tags
 */
function styles($styles){
    return AssetService::styles($styles);
}//function styles

/**
 * Make html output for the string and bold till at the curtain position
 *
 * @param $input string
 * @param $position integer
 * @return string
 */
function partial_bold($input,$position){
    return "<b>".substr($input,0,(integer)$position)."</b>".substr($input,(integer)$position);
}//function acronyme

/**
 * Output application accronyme
 *
 * @return string
 */
function accronyme(){
    $accronyme = setting('application.accronyme');
    return partial_bold($accronyme,1);
}

/**
 * Output application name with decorated html
 *
 * @return string
 */
function app_name_decorated(){
    $appName = setting('application.name');
    $appNameBold = setting('application.name.bold');
    return partial_bold($appName,$appNameBold);
}

/**
 * Output menu from the given menu name which looking from the resources/menu/menuname.yaml
 *
 * @param $menu string menu name
 * @return string html output
 */
function menu($menu){
    return MenuService::getArray($menu);
}

/**
 * Get authenticated user instance
 *
 * @return \Palamike\Foundation\Models\Auth\User
 */
function user(){
    return Auth::user();
}

/**
 * Get url of the authenticated user avatar
 *
 * @return string
 */
function avatar(){
    $avatar =  user()->avatar;
    if(!empty($avatar)){
        return url($avatar->web_path);
    }
    else{
        return url('assets/images/no-avatar.png');
    }
}

/**
 * Get authenticated user role
 *
 * @return \Palamike\Foundation\Models\Auth\Role
 */
function role(){
    return Auth::user()->roles->first();
}

/**
 * Generate breadcrumb html output
 *
 * @param $icon string fontawesome compatible icon name
 * @param $items array of links in the form of ['label' => 'link' ],
 * the link will use url function to generate real url
 * @return string output html
 * @throws Exception
 * @throws Throwable
 */
function breadcrumb($icon,$items){
    return view('foundation::layouts.partials.breadcrumb',compact('icon','items'))->render();
}

/**
 * Replace double underscore in string with '.'
 *
 * @param $text string
 * @return string
 */
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

/**
 * Replace dot ('.') with underscore ('_')
 *
 * @param $str string
 * @return string
 */
function dot_dash($str){
    return str_replace('.','_',$str);
}

/**
 * Replace underscore ('_') with dot ('.')
 *
 * @param $str
 * @return string
 */
function dash_dot($str){
    return str_replace('_','.',$str);
}

/**
 * Map the object arrays with given key field in object
 *
 * @param $key string key field name
 * @param $objects array
 * @param $numeric boolean if this param is set to true it will prepend '_' before key
 *
 * @return array
 */
function object_array_map($key,$objects,$numeric = false){
    $map = [];
    foreach($objects as $object){
        if($numeric){
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

/**
 * Output the current carbon date to formatted string
 * 
 * @param $format
 * @return string
 */
function date_now($format){
    return \Carbon\Carbon::now()->format($format);
}

/**
 * Finish Path with DIRECTORY_SEPARATOR
 * 
 * @param $path
 * @return string
 */
function finish_path($path){
    return str_finish(str_replace('/',DIRECTORY_SEPARATOR,$path),DIRECTORY_SEPARATOR);
}

/**
 * Replace '/' with DIRECTORY_SEPARATOR
 * 
 * @param $path
 * @return string
 */
function replace_path($path){
    return str_replace('/',DIRECTORY_SEPARATOR,$path);
}