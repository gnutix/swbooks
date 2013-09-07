<?php

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\YamlLibraryFactory as BaseYamlLibraryFactory;

/**
 * Library Factory for the XML data
 *
 * @method \Gnutix\StarWarsLibrary\Model\Library getLibrary()   This allows the auto-completion to work correctly
 */
class YamlLibraryFactory extends BaseYamlLibraryFactory
{
    /**
     * {@inheritDoc}
     */
    protected function getClassesNames()
    {
        return array_merge(
            parent::getClassesNames(),
            array(
                // Override the parent's classes names
                'book' => '\Gnutix\StarWarsLibrary\Model\Book',
                'library' => '\Gnutix\StarWarsLibrary\Model\Library',

                // Add new ones
                'chronologicalMarker' => '\Gnutix\StarWarsLibrary\Model\ChronologicalMarker',
                'era' => '\Gnutix\StarWarsLibrary\Model\Era',
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getLibraryDependencies(array $data)
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            array(
                'rawData' => $data,
                'eras' => $this->buildClassInstanceFromArray($data['eras'], 'era'),
            )
        );
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
