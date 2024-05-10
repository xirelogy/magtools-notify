<?php

namespace MagpieTools\Notify\Providers\Strategies;

use Magpie\General\DateTimes\Duration;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifySendable;
use MagpieTools\Notify\System\QueuedSenders;

/**
 * Default strategy to handle notification sender in deferred queue
 */
class DefaultQueuedNotificationSenderStrategy extends QueuedNotificationSenderStrategy
{
    /**
     * @var string|null Final sender's category
     */
    protected readonly ?string $category;
    /**
     * @var Duration Delay before running
     */
    protected readonly Duration $delay;


    /**
     * Constructor
     * @param string|null $category
     * @param Duration|null $delay
     */
    protected function __construct(?string $category, ?Duration $delay)
    {
        $this->category = $category;
        $this->delay = $delay ?? Duration::inSeconds(1);
    }


    /**
     * @inheritDoc
     */
    public function getQueuedDelay(Notifiable $notification) : Duration
    {
        return $this->delay;
    }


    /**
     * @inheritDoc
     */
    public function getFinalSenderInQueue(Notifiable $notification) : ?NotifySendable
    {
        return QueuedSenders::getSender($this->category);
    }


    /**
     * Create an instance
     * @param string|null $category
     * @param Duration|int|null $delay
     * @return static
     */
    public static function create(?string $category = null, Duration|int|null $delay = null) : static
    {
        return new static($category, Duration::accept($delay));
    }
}