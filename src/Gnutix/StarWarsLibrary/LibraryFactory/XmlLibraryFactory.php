<?php

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\XmlLibraryFactory as BaseXmlLibraryFactory;

/**
 * Library Factory for the XML data
 *
 * @method \Gnutix\StarWarsLibrary\Model\Library getLibrary()   This allows the auto-completion to work correctly
 */
class XmlLibraryFactory extends BaseXmlLibraryFactory
{
    /**
     * {@inheritDoc}
     */
    protected function getClassesMap()
    {
        return array_merge(
            parent::getClassesMap(),
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
    protected function getLibraryDependencies(\SimpleXMLElement $data)
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            array(
                'eras' => $this->buildClassInstanceFromNodeAttributes($data, '//books/era', 'era'),
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getBooksDependencies(\SimpleXMLElement $data, \SimpleXMLElement $book)
    {
        $era = $book->xpath('parent::era');

        return array_merge(
            parent::getBooksDependencies($data, $book),
            array(
                'chronologicalMarker' => new $this->classes['chronologicalMarker'](
                    array(
                        'timeStart' => (string) $book->{'time'},
                        'era' => new $this->classes['era']($this->getSimpleXmlElementAttributesAsArray(reset($era))),
                    )
                )
            )
        );
    }
}
