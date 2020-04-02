<?php

namespace Gnutix\StarWarsLibrary\Dumper;

use Gnutix\Library\Dumper\YamlLibraryDumper as BaseYamlLibraryDumper;
use Gnutix\StarWarsLibrary\Model\Book;
use Gnutix\StarWarsLibrary\Model\ChronologicalMarker;
use Gnutix\StarWarsLibrary\StarWarsLibraryInterface;

/**
 * YAML Library Dumper
 */
final class YamlLibraryDumper extends BaseYamlLibraryDumper
{
    /**
     * @param string|int $dateInput
     *
     * @return string
     */
    public function transformToSplitYamlDate($dateInput)
    {
        $dates = [];

        foreach (explode(' - ', trim((string) $dateInput)) as $date) {
            $date = str_replace(' ', '', $date);
            $date = str_replace('ABY', '', $date);

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

    protected function buildArray(StarWarsLibraryInterface $library)
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

    protected function buildBookArray(Book $book)
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

    /**
     * @return array
     */
    protected function buildTimeArray(ChronologicalMarker $chronologicalMarker)
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
