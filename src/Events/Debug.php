<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 4/7/2016 AD
 * Time: 1:49 PM
 */

namespace Palamike\Foundation\Events;


class Debug extends FoundationLogEvent{


    /**
     * Debug constructor.
     */
    public function __construct($message)
    {
        parent::__construct('debug',$message);
    }
}