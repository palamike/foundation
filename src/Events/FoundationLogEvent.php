<?php

namespace Palamike\Foundation\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

abstract class FoundationLogEvent extends Event
{
    use SerializesModels;

    /**
     * @var $type 
     * 
     * Log Types determine the type of current logging
     * 'access' is authentication log.
     * 'store' is the database store and modification log.
     * 'navigation' is the page navigation log.
     * 'query' is the database query log.
     * 'debug' is the debug log (send debug message to log).
     */
    protected $type;
    protected $message;
    protected $data;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type,$message,$data = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * get Log Type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * get Log Message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * get Log Data
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
