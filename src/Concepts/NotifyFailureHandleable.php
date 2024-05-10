<?php

namespace MagpieTools\Notify\Concepts;

use MagpieTools\Notify\Objects\NotificationSent;
use MagpieTools\Notify\Providers\NotificationSender;
use Throwable;

/**
 * May handle notification failure
 */
interface NotifyFailureHandleable
{
    /**
     * Handle failure message while sending out notification
     * @param NotificationSender $sender
     * @param Notifiable $notification
     * @param Throwable $ex
     * @return NotificationSent|null
     */
    public function handleSendNotificationFailure(NotificationSender $sender, Notifiable $notification, Throwable $ex) : ?NotificationSent;
}