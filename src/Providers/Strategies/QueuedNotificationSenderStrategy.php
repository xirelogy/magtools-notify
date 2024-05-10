<?php

namespace MagpieTools\Notify\Providers\Strategies;

use Magpie\General\DateTimes\Duration;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifySendable;

/**
 * Strategy to handle notification sender in deferred queue
 */
abstract class QueuedNotificationSenderStrategy
{
    /**
     * Delay before the queued notification is processed
     * @param Notifiable $notification
     * @return Duration
     */
    public abstract function getQueuedDelay(Notifiable $notification) : Duration;


    /**
     * Get the final notification to be sent out, when in deferred queue
     * @param Notifiable $notification
     * @return Notifiable|null
     */
    public function getFinalNotificationInQueue(Notifiable $notification) : ?Notifiable
    {
        return $notification;
    }


    /**
     * Get the final notification sender to send out notification, when in deferred queue
     * @param Notifiable $notification
     * @return NotifySendable|null
     */
    public abstract function getFinalSenderInQueue(Notifiable $notification) : ?NotifySendable;
}