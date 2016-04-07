<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 1:12 PM
 */

namespace Palamike\Foundation\Events;


use Illuminate\Support\Facades\Auth;

class DataQuery extends FoundationLogEvent{


    /**
     * DataQuery constructor.
     * @var $query object is the database query object contains 3 fields which are
     * 'sql' = sql prepare statement
     * 'bindings' = data bindings
     * 'time' = query usage time
     */
    public function __construct($query)
    {
        $user = Auth::user();
        $userInfo = "[{$user->name}(id:{$user->id})]";
        $messages = $userInfo."has query data.\n";
        $messages .= $userInfo."======================================================\n";
        $messages .= $userInfo."[sql]".$query->sql."\n";
        $messages .= $userInfo."[bindings]".var_export($query->bindings,true)."\n";
        $messages .= $userInfo."[time]".$query->time."\n";
        $messages .= $userInfo."======================================================\n";
        
        parent::__construct('query',$messages);
    }
}