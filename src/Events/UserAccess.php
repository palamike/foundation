<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 12:44 PM
 */

namespace Palamike\Foundation\Events;


class UserAccess extends FoundationLogEvent{

    /**
     * UserAccess constructor.
     * @var $action string
     * action contain the following values
     * 'attempt', 'logged_in', 'logged_out'
     * 
     * @var $data
     * data may have the following array key
     * 'user' is the Eloquent user instance
     * 'login using' is the dynamic key which can set on the setting. 
     */
    public function __construct($action,$data)
    {
        switch($action){
            case 'attempt' :
                $using = setting('login.using');
                $message = $data[$using].' attempt to login.';
                braek;
            case 'logged_in' :
                $message = $data['user']->name."({$data['user']->id})".' has logged in.';
                braek;
            case 'logged_out' :
                $message = $data['user']->name."({$data['user']->id})".' has logged out.';
                braek;
            default :
                $message = 'User has access the server.';
        }
        
        parent::__construct('access',$message);
    }
}