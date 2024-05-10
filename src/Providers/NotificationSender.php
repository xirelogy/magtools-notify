<?php

namespace MagpieTools\Notify\Providers;

use Magpie\Codecs\Parsers\ClosureParser;
use Magpie\Codecs\Parsers\StringParser;
use Magpie\Configurations\EnvKeySchema;
use Magpie\Configurations\EnvParserHost;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\PersistenceException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\StreamException;
use Magpie\General\Concepts\TypeClassable;
use Magpie\General\Factories\ClassFactory;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifySendable;
use MagpieTools\Notify\Objects\NotificationSent;

/**
 * Support to send out notifications
 */
abstract class NotificationSender implements NotifySendable, TypeClassable
{
    /**
     * Sending out the notification
     * @param Notifiable $notification
     * @return NotificationSent|null
     * @throws SafetyCommonException
     * @throws PersistenceException
     * @throws StreamException
     */
    public final function sendNotification(Notifiable $notification) : ?NotificationSent
    {
        return $this->onSendNotification($notification);
    }


    /**
     * Sending out the notification according to current method
     * @param Notifiable $notification
     * @return NotificationSent|null
     * @throws SafetyCommonException
     * @throws StreamException
     * @throws PersistenceException
     */
    protected abstract function onSendNotification(Notifiable $notification) : ?NotificationSent;


    /**
     * Parse and create from given environment parser host and key
     * @param EnvParserHost $parserHost
     * @param string $key
     * @return static
     * @throws SafetyCommonException
     */
    public static final function parseFromEnv(EnvParserHost $parserHost, string $key) : static
    {
        $envKey = new EnvKeySchema('NOTIFY', $key);

        $classParser = ClosureParser::create(function (mixed $value, ?string $hintName) : string {
            $value = StringParser::create()->parse($value, $hintName);
            return ClassFactory::resolve($value, self::class);
        });

        $className = $parserHost->requires($envKey->key('TYPE'), $classParser);
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return $className::onParseFromEnv($parserHost, $envKey);
    }


    /**
     * Parse and create from given environment parser host and environment key
     * @param EnvParserHost $parserHost
     * @param EnvKeySchema $envKey
     * @return static
     * @throws SafetyCommonException
     */
    protected static abstract function onParseFromEnv(EnvParserHost $parserHost, EnvKeySchema $envKey) : static;
}