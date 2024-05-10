<?php

namespace MagpieTools\Notify\Providers\Queues;

use Magpie\Queues\BaseQueueRunnable;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Providers\Strategies\QueuedNotificationSenderStrategy;

/**
 * Queue item to send out notification (deferred)
 */
final class QueuedNotificationSenderRunner extends BaseQueueRunnable
{
    /**
     * @var Notifiable Target notification
     */
    protected readonly Notifiable $notification;
    /**
     * @var QueuedNotificationSenderStrategy Handling strategy
     */
    protected readonly QueuedNotificationSenderStrategy $strategy;


    /**
     * Constructor
     * @param Notifiable $notification
     * @param QueuedNotificationSenderStrategy $strategy
     */
    public function __construct(Notifiable $notification, QueuedNotificationSenderStrategy $strategy)
    {
        $this->notification = $notification;
        $this->strategy = $strategy;
    }


    /**
     * @inheritDoc
     */
    protected function onRun() : void
    {
        $finalNotification = $this->strategy->getFinalNotificationInQueue($this->notification);
        if ($finalNotification === null) return;

        $finalSender = $this->strategy->getFinalSenderInQueue($this->notification);
        if ($finalSender === null) return;

        $finalSender->sendNotification($finalNotification);
    }
}