<?php

declare(strict_types=1);

namespace Gnutix\Library\Model;

use DateTime;
use Gnutix\Library\Helper\ArrayPopulatedObject;

final class Release extends ArrayPopulatedObject
{
    protected string $title;
    protected Language $language;
    protected Editor $editor;
    protected Format $format;
    protected ?DateTime $publicationDate;
    protected Series $series;
    protected Owner $owner;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getEditor(): Editor
    {
        return $this->editor;
    }

    public function getFormat(): Format
    {
        return $this->format;
    }

    public function getPublicationDate(): ?DateTime
    {
        return $this->publicationDate;
    }

    public function isPublished(): bool
    {
        return $this->publicationDate < new DateTime();
    }

    public function getSeries(): Series
    {
        return $this->series;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }
}
