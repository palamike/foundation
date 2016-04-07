<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 12:45 PM
 */

namespace Palamike\Foundation\Events;


use Illuminate\Support\Facades\Auth;

class DataStore extends FoundationLogEvent{

    /**
     * DataStore constructor.
     * @param $className string represent the eloquent class name 
     * @param $oldValue string represent the old data it may be json string
     * @param $newValue string represent the new data it may be json string
     */
    public function __construct($className,$oldValue,$newValue)
    {
        $user = Auth::user();
        $message = "[{$user->name}(id:{$user->id})]"."The $className data has been modified from $oldValue to $newValue";
        parent::__construct('store',$message);
    }

}