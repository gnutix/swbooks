<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\Dumper;

use Gnutix\Library\Dumper\YamlLibraryDumper as BaseYamlLibraryDumper;
use Gnutix\Library\LibraryInterface;
use Gnutix\Library\Model\Book;
use Gnutix\StarWarsLibrary\Model\Book as StarWarsBook;
use Gnutix\StarWarsLibrary\Model\ChronologicalMarker;
use Gnutix\StarWarsLibrary\Model\Library as StarWarsLibrary;

final class YamlLibraryDumper extends BaseYamlLibraryDumper
{
    /**
     * @param string|float|null $dateInput
     */
    public function transformToSplitYamlDate($dateInput): array
    {
        $dates = [];

        foreach (explode(' - ', trim((string) $dateInput)) as $date) {
            $date = str_replace([' ', 'ABY'], '', $date);

            if (false !== strpos($date, 'BBY')) {
                $date = '-'.trim(str_replace('BBY', '', $date));
            }

            $dates[] = $date;
        }

        $results = $dates;

        return [
            'start' => trim($results[0]),
            'end' => isset($results[1]) ? trim($results[1]) : null,
        ];
    }

    /**
     * @param LibraryInterface&StarWarsLibrary $library
     */
    protected function buildArray(LibraryInterface $library): array
    {
        $eras = [];

        foreach ($library->getEras() as $era) {
            $eras[] = [
                'id' => '&era_'.$era->getId().'/'.$era->getId(),
                'name' => $era->getName(),
            ];
        }

        return array_merge([
            'eras' => $eras,
        ], parent::buildArray($library));
    }

    /**
     * @param Book&StarWarsBook $book
     */
    protected function buildBookArray(Book $book): array
    {
        return array_merge(
            [
                'starWars' => [
                    'chronology' => [
                        'era' => '*era_'.$book->getChronologicalMarker()->getEra()->getId(),
                        'time' => $this->buildTimeArray($book->getChronologicalMarker()),
                    ],
                    'swuBookId' => null,
                ],
            ],
            parent::buildBookArray($book)
        );
    }

    protected function buildTimeArray(ChronologicalMarker $chronologicalMarker): array
    {
        $start = $chronologicalMarker->getTimeStart();

        if (empty($start)) {
            $start = null;
            $end = null;
        } else {
            $date = $this->transformToSplitYamlDate($start);
            $start = $date['start'];
            $end = $date['end'];
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }
}
