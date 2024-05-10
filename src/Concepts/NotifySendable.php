<?php

namespace MagpieTools\Notify\Concepts;

use Magpie\Exceptions\PersistenceException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\StreamException;
use MagpieTools\Notify\Objects\NotificationSent;

/**
 * May send out (or handle) notification
 */
interface NotifySendable
{
    /**
     * Sending out the notification
     * @param Notifiable $notification
     * @return NotificationSent|null
     * @throws SafetyCommonException
     * @throws PersistenceException
     * @throws StreamException
     */
    public function sendNotification(Notifiable $notification) : ?NotificationSent;
}