<?php

namespace MagpieTools\Notify\Objects;

use Magpie\General\Concepts\BinaryDataProvidable;
use Magpie\Objects\CommonObject;
use MagpieTools\Notify\Concepts\Notifiable;

/**
 * A general (simple) notification
 */
class GeneralNotification extends CommonObject implements Notifiable
{
    /**
     * @var string|null Source of the notification
     */
    public readonly ?string $source;
    /**
     * @var string|null Notification title
     */
    public readonly ?string $title;
    /**
     * @var string Notification content
     */
    public readonly string $content;
    /**
     * @var array<BinaryDataProvidable> Attachments to the notification
     */
    public readonly array $attachments;


    /**
     * Constructor
     * @param string $content
     * @param string|null $title
     * @param string|null $source
     * @param iterable<BinaryDataProvidable> $attachments
     */
    public function __construct(string $content, ?string $title = null, ?string $source = null, iterable $attachments = [])
    {
        $this->source = $source;
        $this->title = $title;
        $this->content = $content;
        $this->attachments = iter_flatten($attachments, false);
    }


    /**
     * @inheritDoc
     */
    public function getSource() : ?string
    {
        return $this->source;
    }


    /**
     * @inheritDoc
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }


    /**
     * @inheritDoc
     */
    public function getContent() : string
    {
        return $this->content;
    }


    /**
     * @inheritDoc
     */
    public function getAttachments() : iterable
    {
        return $this->attachments;
    }
}