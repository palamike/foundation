<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/21/2016 AD
 * Time: 5:06 PM
 */

use Illuminate\Support\Facades\App;

function setting_route($name,$action = 'index'){

    $className = '\App\Http\Controllers\Setting\\'.ucfirst($name).'Controller';

    if(!class_exists($className)){
        $className = '\Palamike\Foundation\Http\Controllers\Setting\\'.ucfirst($name).'Controller';
    }

    if(!class_exists($className)){
        throw new \Exception('Setting Controller Class does not exists !!');
    }

    $controller = App::make($className);
    return $controller->callAction($action, ['name' => $name]);
}