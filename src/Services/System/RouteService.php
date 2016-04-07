<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/21/2016 AD
 * Time: 5:01 PM
 */

namespace Palamike\Foundation\Services\System;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class RouteService {
    public static function routes(){
        $prefix = Config::get('foundation.route.prefix');

        static::auth($prefix);

        if(empty($prefix)){
            Route::group([
                'namespace' => 'Palamike\\Foundation\\Http\\Controllers',
                'middleware' => ['web','auth']
            ], function () {
                static::foundation();
            });
        }
        else{
            Route::group([
                'prefix' => $prefix ,
                'namespace' => 'Palamike\\Foundation\\Http\\Controllers',
                'middleware' => ['web','auth'] ], function () {
                    static::foundation();
            });
        }
    }

    private static function auth($prefix = null){

        if(empty($prefix)){
            Route::group(['middleware' => 'web'], function () {
                Route::auth();
            });
        }
        else{
            Route::group([
                'prefix' => $prefix ,
                'middleware' => 'web'
            ], function () {
                Route::auth();
            });
        }
    }

    private static function foundation(){

        Route::get('setting/{name}', ['as' => 'setting.index', function($name){
            return setting_route($name);
        }]);

        Route::get('setting/{name}/edit', ['as' => 'setting.edit', function($name){
            return setting_route($name,'edit');
        }]);

        Route::put('setting/{name}/update', ['as' => 'setting.update', function($name){
            return setting_route($name,'update');
        }]);

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth' ],function(){
            Route::get('user/list', ['as' => 'auth.user.list', 'uses' => 'UserController@query']);
            Route::resource('user','UserController',[
                'except' => [ 'show']
            ]);

            Route::get('role/list', ['as' => 'auth.role.list', 'uses' => 'RoleController@query']);
            Route::resource('role','RoleController',[
                'except' => [ 'show']
            ]);

            Route::resource('profile','ProfileController',[
                'except' => [ 'show']
            ]);
        });

        Route::group(['prefix' => 'media', 'namespace' => 'Media' ],function(){
            Route::post('upload',['as' => 'media.upload', 'uses' => 'MediaController@upload']);
            Route::delete('delete/{media}',['as' => 'media.delete', 'uses' => 'MediaController@delete']);
        });
    }
}