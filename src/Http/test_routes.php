<?php

Route::group(['prefix' => 'test','middleware' => ['web','auth'],'namespace' => 'Test'], function () {
    Route::get('vue/application', ['as' => 'test.vue.app', 'uses' => 'VueElementTestController@application']);
    Route::get('vue/query', ['as' => 'test.vue.query', 'uses' => 'VueElementTestController@query']);
    Route::resource('vue','VueElementTestController',[
        'except' => ['show']
    ]);
});