<?php

namespace MagpieTools\Notify\Providers;

use Magpie\Codecs\Parsers\CommaArrayParser;
use Magpie\Codecs\Parsers\StringParser;
use Magpie\Configurations\EnvParserHost;
use Magpie\Exceptions\SafetyCommonException;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifyFailureHandleable;
use MagpieTools\Notify\Concepts\NotifySendable;
use MagpieTools\Notify\Objects\MultipleNotificationSent;
use MagpieTools\Notify\Objects\NotificationSent;
use Throwable;

/**
 * Collection of all notification senders
 */
class NotificationSenders implements NotifySendable
{
    /**
     * @var array<NotificationSender> All senders
     */
    protected readonly array $senders;
    /**
     * @var NotifyFailureHandleable|null Specific failure handler
     */
    protected ?NotifyFailureHandleable $failureHandle = null;


    /**
     * Constructor
     * @param iterable<NotificationSender> $senders
     */
    protected function __construct(iterable $senders)
    {
        $this->senders = iter_flatten($senders, false);
    }


    /**
     * Specify the failure handler
     * @param NotifyFailureHandleable $handle
     * @return $this
     */
    public function withFailureHandler(NotifyFailureHandleable $handle) : static
    {
        $this->failureHandle = $handle;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public final function sendNotification(Notifiable $notification) : ?NotificationSent
    {
        return MultipleNotificationSent::create($this->onSendNotification($notification));
    }


    /**
     * Send out notification via all the senders
     * @param Notifiable $notification
     * @return iterable<NotificationSent|null>
     */
    private function onSendNotification(Notifiable $notification) : iterable
    {
        foreach ($this->senders as $sender) {
            yield $this->sendNotificationUsing($sender, $notification);
        }
    }


    /**
     * Send out notification using given sender
     * @param NotificationSender $sender
     * @param Notifiable $notification
     * @return NotificationSent|null
     */
    protected function sendNotificationUsing(NotificationSender $sender, Notifiable $notification) : ?NotificationSent
    {
        try {
            return $sender->sendNotification($notification);
        } catch (Throwable $ex) {
            return $this->handleSendNotificationFailure($sender, $notification, $ex);
        }
    }


    /**
     * Handle failure message while sending out notification
     * @param NotificationSender $sender
     * @param Notifiable $notification
     * @param Throwable $ex
     * @return NotificationSent|null
     */
    protected function handleSendNotificationFailure(NotificationSender $sender, Notifiable $notification, Throwable $ex) : ?NotificationSent
    {
        return $this->failureHandle?->handleSendNotificationFailure($sender, $notification, $ex);
    }


    /**
     * Parse and create from given environment parser host and specific key (defaults to NOTIFIERS)
     * @param EnvParserHost $parserHost
     * @param string $key
     * @return static
     * @throws SafetyCommonException
     */
    public static final function parseFromEnv(EnvParserHost $parserHost, string $key = 'NOTIFIERS') : static
    {
        return new static(static::parseSendersFromEnv($parserHost, $key));
    }


    /**
     * Parse for all the senders
     * @param EnvParserHost $parserHost
     * @param string $key
     * @return iterable<NotificationSender>
     * @throws SafetyCommonException
     */
    protected static final function parseSendersFromEnv(EnvParserHost $parserHost, string $key) : iterable
    {
        $keys = $parserHost->optional($key, CommaArrayParser::create()->withChain(StringParser::create()));
        foreach ($keys as $key) {
            yield NotificationSender::parseFromEnv($parserHost, $key);
        }
    }
}