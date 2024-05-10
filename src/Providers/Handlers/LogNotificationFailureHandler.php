<?php

namespace MagpieTools\Notify\Providers\Handlers;

use Magpie\Facades\Log;
use Magpie\General\Sugars\Quote;
use Magpie\Logs\Concepts\Loggable;
use MagpieTools\Notify\Concepts\Notifiable;
use MagpieTools\Notify\Concepts\NotifyFailureHandleable;
use MagpieTools\Notify\Objects\NotificationSent;
use MagpieTools\Notify\Providers\NotificationSender;
use Throwable;

/**
 * Handle notification failure by writing to logs
 */
class LogNotificationFailureHandler implements NotifyFailureHandleable
{
    /**
     * @var Loggable Logging target
     */
    protected readonly Loggable $logger;
    /**
     * @var bool If log the exception trace
     */
    protected bool $isTrace;


    /**
     * Constructor
     * @param Loggable|null $logger
     * @param bool $isTrace
     */
    protected function __construct(?Loggable $logger, bool $isTrace)
    {
        $this->logger = $logger ?? Log::current();
        $this->isTrace = $isTrace;
    }


    /**
     * Specify if tracing is enabled
     * @param bool $isTrace
     * @return $this
     */
    public function withTrace(bool $isTrace = true) : static
    {
        $this->isTrace = $isTrace;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function handleSendNotificationFailure(NotificationSender $sender, Notifiable $notification, Throwable $ex) : ?NotificationSent
    {
        $this->logger->warning(_format_l('Unexpected exception while sending notification', 'Unexpected exception while sending notification of {{0}}: {{1}}', Quote::single($sender::class), $ex->getMessage()));
        if ($this->isTrace) {
            $this->logger->debug('    ' . $ex->getTraceAsString());
        }

        $loopEx = $ex->getPrevious();
        while ($loopEx !== null) {
            $this->logger->warning(_format_l('.. Caused by exception', '.. Caused by exception: {{0}}', $ex->getMessage()));
            if ($this->isTrace) {
                $this->logger->debug('    ' . $ex->getTraceAsString());
            }
            $loopEx = $loopEx->getPrevious();
        }

        return null;
    }


    /**
     * Create an instance
     * @param Loggable|null $logger
     * @param bool $isTrace
     * @return static
     */
    public static function create(?Loggable $logger = null, bool $isTrace = false) : static
    {
        return new static($logger, $isTrace);
    }
}