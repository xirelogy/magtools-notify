<?php

namespace MagpieTools\Notify\Objects;

use Magpie\General\Packs\PackContext;

/**
 * Result handle for multiple sent notification
 */
class MultipleNotificationSent extends NotificationSent
{
    /**
     * @var array<NotificationSent|null> Sub items
     */
    public readonly array $items;


    /**
     * Constructor
     * @param iterable<NotificationSent|null> $items
     */
    protected function __construct(iterable $items)
    {
        $this->items = iter_flatten($items, false);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->items = $this->items;
    }


    /**
     * Create an instance
     * @param iterable<NotificationSent|null> $items
     * @return static
     */
    public static function create(iterable $items) : static
    {
        return new static($items);
    }
}