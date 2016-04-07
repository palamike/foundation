<?php

namespace Palamike\Foundation\Listeners;

use Illuminate\Contracts\Logging\Log;
use Palamike\Foundation\Events\FoundationLogEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FoundationLogListener
{

    private $logger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Log $log)
    {
        $this->logger = $log;
    }

    /**
     * Handle the event.
     *
     * @param  FoundationLogEvent  $event
     * @return void
     */
    public function handle(FoundationLogEvent $event)
    {
        $this->logger->info($event->getMessage());
    }
}
