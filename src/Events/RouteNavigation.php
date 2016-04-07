<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 1:06 PM
 */

namespace Palamike\Foundation\Events;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RouteNavigation extends FoundationLogEvent{


    /**
     * RouteNavigation constructor.
     */
    public function __construct()
    {
        $routeName = Route::currentRouteName();
        $actionName = Route::currentRouteAction();
        $user = Auth::user();
        $message = "[{$user->name}(id:{$user->id})]"."user has been navigate to route $routeName and action $actionName";
        parent::__construct('navigation',$message);
    }
}