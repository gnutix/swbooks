<?php

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\YamlLibraryFactory as BaseYamlLibraryFactory;
use Gnutix\StarWarsLibrary\Dumper\YamlLibraryDumper;

/**
 * Library Factory for the XML data
 *
 * @method \Gnutix\StarWarsLibrary\Model\Library getLibrary()    This allows the auto-completion to work correctly
 */
class YamlLibraryFactory extends BaseYamlLibraryFactory
{
    /**
     * {@inheritDoc}
     */
    protected function getLibraryDependencies(array $data)
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            array(
                'eras' => $this->buildClassInstanceFromArray($data['eras'], 'era'),
            )
        );
    }

    /**
     * @return \Gnutix\StarWarsLibrary\Dumper\YamlLibraryDumper
     */
    public function getLibraryDumper()
    {
        return new YamlLibraryDumper();
    }

    /**
     * {@inheritDoc}
     */
    protected function getBookDependencies(array $book)
    {
        $starWarsNode = $this->get($book, 'starWars', array());

        return array_merge(
            parent::getBookDependencies($book),
            array(
                'chronologicalMarker' => new $this->classes['chronologicalMarker'](
                    $this->buildChronologicalMarkerDependencies(
                        $this->get($starWarsNode, 'chronology', array())
                    )
                ),
                'swuBookId' => $this->get($starWarsNode, 'swuBookId'),
            )
        );
    }

    /**
     * @param array $chronologicalMarker
     *
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

        return array(
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'era' => new $this->classes['era']($this->get($chronologicalMarker, 'era', array())),
        );
    }
}
