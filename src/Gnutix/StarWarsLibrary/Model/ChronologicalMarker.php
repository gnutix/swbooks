<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\Model;

use Gnutix\Library\Helper\ArrayPopulatedObject;

final class ChronologicalMarker extends ArrayPopulatedObject
{
    protected ?float $timeStart;
    protected ?float $timeEnd;
    protected Era $era;

    public function getTimeStart(): ?float
    {
        return $this->timeStart;
    }

    public function getTimeEnd(): ?float
    {
        return $this->timeEnd;
    }

    public function getEra(): Era
    {
        return $this->era;
    }
}
