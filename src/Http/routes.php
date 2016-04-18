<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 2:16 PM
 */

Route::group(['middleware' => ['web','auth.redirect']], function () {
    Route::auth();
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth' ],function(){
    Route::resource('profile','ProfileController',[
        'only' => [ 'index', 'edit' , 'update' ]
    ]);

    Route::resource('user','UserController');
});