<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\YamlLibraryFactory as BaseYamlLibraryFactory;
use Gnutix\StarWarsLibrary\Dumper\YamlLibraryDumper;
use Gnutix\StarWarsLibrary\Model\Library;

/**
 * @method Library getLibrary()
 */
final class YamlLibraryFactory extends BaseYamlLibraryFactory
{
    public function getLibraryDumper(): YamlLibraryDumper
    {
        return new YamlLibraryDumper();
    }

    protected function getLibraryDependencies(array $data): array
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            [
                'eras' => $this->buildClassInstanceFromArray($data['eras'], 'era'),
            ]
        );
    }

    protected function getBookDependencies(array $book): array
    {
        $starWarsNode = $this->get($book, 'starWars', []);

        return array_merge(
            parent::getBookDependencies($book),
            [
                'chronologicalMarker' => new $this->classes['chronologicalMarker'](
                    $this->buildChronologicalMarkerDependencies($this->get($starWarsNode, 'chronology', []))
                ),
                'swuBookId' => $this->get($starWarsNode, 'swuBookId'),
            ]
        );
    }

    protected function buildChronologicalMarkerDependencies(array $chronologicalMarker): array
    {
        $timeEnd = null;
        $timeStart = $time = $this->get($chronologicalMarker, 'time');
        if (is_array($time)) {
            $timeStart = $this->get($time, 'start');
            $timeEnd = $this->get($time, 'end');
        }

        return [
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'era' => new $this->classes['era']($this->get($chronologicalMarker, 'era', [])),
        ];
    }
}
