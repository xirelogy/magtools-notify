<?php

namespace MagpieTools\Notify\Objects;

use Magpie\General\Packs\PackContext;
use Magpie\Models\Identifier;

/**
 * Queued-up sent notification
 */
class QueuedNotificationSent extends NotificationSent
{
    /**
     * @var Identifier|string|int Associated ID of the queued item
     */
    public readonly Identifier|string|int $queueItemId;


    /**
     * Constructor
     * @param Identifier|string|int $queueItemId
     */
    public function __construct(Identifier|string|int $queueItemId)
    {
        $this->queueItemId = $queueItemId;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->queueItemId = $this->queueItemId;
    }
}