<?php

namespace MagpieTools\Notify\Concepts;

/**
 * May classify a notification
 */
interface NotifyClassifiable
{
    /**
     * Classify the specific notification
     * @param Notifiable $notification
     * @return string|null
     */
    public function getNotificationClassification(Notifiable $notification) : ?string;
}