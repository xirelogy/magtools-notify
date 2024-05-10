<?php

namespace MagpieTools\Notify\System;

use Magpie\General\Sugars\Quote;
use Magpie\General\Traits\StaticClass;
use MagpieTools\Notify\Concepts\NotifySendable;

/**
 * Manage registry of senders in deferred queues
 */
class QueuedSenders
{
    use StaticClass;

    /**
     * @var array<string, NotifySendable> Registry of senders
     */
    protected static array $senders = [];


    /**
     * Register a sender (under given category)
     * @param NotifySendable $sender
     * @param string|null $category
     * @return void
     */
    public static function registerSender(NotifySendable $sender, ?string $category = null) : void
    {
        $categoryKey = static::makeKey($category);
        static::$senders[$categoryKey] = $sender;
    }


    /**
     * Try to look for a sender for corresponding category
     * @param string|null $category
     * @return NotifySendable|null
     */
    public static function getSender(?string $category = null) : ?NotifySendable
    {
        $categoryKey = static::makeKey($category);
        return static::$senders[$categoryKey] ?? null;
    }


    /**
     * Create a key for specific category
     * @param string|null $category
     * @return string
     */
    protected static function makeKey(?string $category) : string
    {
        if ($category === null) return '';
        return Quote::bracket($category);
    }
}