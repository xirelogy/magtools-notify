<?php

namespace MagpieTools\Notify\Concepts;

use Magpie\General\Concepts\BinaryDataProvidable;

/**
 * General notification interface
 */
interface Notifiable
{
    /**
     * Source of notification
     * @return string|null
     */
    public function getSource() : ?string;


    /**
     * Notification title
     * @return string|null
     */
    public function getTitle() : ?string;


    /**
     * Notification content (body)
     * @return string
     */
    public function getContent() : string;


    /**
     * Attachments to the notification
     * @return iterable<BinaryDataProvidable>
     */
    public function getAttachments() : iterable;
}