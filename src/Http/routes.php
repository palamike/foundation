<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 2:16 PM
 */

Route::group(['middleware' => 'web'], function () {
    Route::auth();
});