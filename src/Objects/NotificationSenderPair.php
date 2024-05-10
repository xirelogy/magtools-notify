<?php

namespace MagpieTools\Notify\Objects;

use Magpie\Objects\CommonObject;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifySendable;

/**
 * Pair of notification / notification sender
 */
class NotificationSenderPair extends CommonObject
{
    /**
     * @var Notifiable Notification instance
     */
    public readonly Notifiable $notification;
    /**
     * @var NotifySendable Notification sender
     */
    public readonly NotifySendable $sender;


    /**
     * Constructor
     * @param Notifiable $notification
     * @param NotifySendable $sender
     */
    public function __construct(Notifiable $notification, NotifySendable $sender)
    {
        $this->notification = $notification;
        $this->sender = $sender;
    }
}