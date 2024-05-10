<?php

namespace MagpieTools\Notify\Providers;

use Magpie\Exceptions\ClassCannotAnonymousException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\System\HardCore\ClassReflection;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifySendable;
use MagpieTools\Notify\Objects\NotificationSent;
use MagpieTools\Notify\Objects\QueuedNotificationSent;
use MagpieTools\Notify\Providers\Queues\QueuedNotificationSenderRunner;
use MagpieTools\Notify\Providers\Strategies\DefaultQueuedNotificationSenderStrategy;
use MagpieTools\Notify\Providers\Strategies\QueuedNotificationSenderStrategy;

/**
 * Send out notification from deferred queue
 */
final class QueuedNotificationSender implements NotifySendable
{
    /**
     * @var QueuedNotificationSenderStrategy Associated strategy
     */
    protected readonly QueuedNotificationSenderStrategy $strategy;


    /**
     * Constructor
     * @param QueuedNotificationSenderStrategy|null $strategy
     * @throws SafetyCommonException
     */
    protected function __construct(?QueuedNotificationSenderStrategy $strategy)
    {
        if ($strategy !== null && ClassReflection::isAnonymous($strategy::class)) {
            throw new ClassCannotAnonymousException(_l('strategy'));
        }

        $this->strategy = $strategy ?? DefaultQueuedNotificationSenderStrategy::create();
    }


    /**
     * @inheritDoc
     */
    public final function sendNotification(Notifiable $notification) : ?NotificationSent
    {
        // Create a runner and dispatch
        $runner = new QueuedNotificationSenderRunner($notification, $this->strategy);
        $queued = $runner->queueDispatch();
        $queued->withDelay($this->strategy->getQueuedDelay($notification));

        return new QueuedNotificationSent($queued->getId());
    }


    /**
     * Create an instance
     * @param QueuedNotificationSenderStrategy|null $strategy
     * @return static
     * @throws SafetyCommonException
     */
    public static function create(?QueuedNotificationSenderStrategy $strategy = null) : static
    {
        return new static($strategy);
    }
}