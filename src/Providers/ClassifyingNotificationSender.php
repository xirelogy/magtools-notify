<?php

namespace MagpieTools\Notify\Providers;

use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifySendable;
use MagpieTools\Notify\Objects\NotificationSent;

/**
 * Classify the notification before sending out
 */
abstract class ClassifyingNotificationSender implements NotifySendable
{
    /**
     * @inheritDoc
     */
    public final function sendNotification(Notifiable $notification) : ?NotificationSent
    {
        $nextSender = $this->getNextSender($notification);
        if ($nextSender === null) return null;

        return $nextSender->sendNotification($notification);
    }


    protected abstract function getNextSender(Notifiable $notification) : ?NotifySendable;
}