<?php

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\YamlLibraryFactory as BaseYamlLibraryFactory;
use Gnutix\StarWarsLibrary\Dumper\YamlLibraryDumper;

/**
 * Library Factory for the YAML data
 *
 * @method \Gnutix\StarWarsLibrary\Model\Library getLibrary()    This allows the auto-completion to work correctly
 */
final class YamlLibraryFactory extends BaseYamlLibraryFactory
{
    /**
     * @return \Gnutix\StarWarsLibrary\Dumper\YamlLibraryDumper
     */
    public function getLibraryDumper()
    {
        return new YamlLibraryDumper();
    }

    protected function getLibraryDependencies(array $data)
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            [
                'eras' => $this->buildClassInstanceFromArray($data['eras'], 'era'),
            ]
        );
    }

    protected function getBookDependencies(array $book)
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

    /**
     * @return array
     */
    protected function buildChronologicalMarkerDependencies(array $chronologicalMarker)
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
