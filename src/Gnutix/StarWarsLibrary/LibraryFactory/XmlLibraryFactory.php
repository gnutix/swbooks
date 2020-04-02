<?php

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\XmlLibraryFactory as BaseXmlLibraryFactory;

/**
 * Library Factory for the XML data
 *
 * @method \Gnutix\StarWarsLibrary\Model\Library getLibrary()    This allows the auto-completion to work correctly
 */
final class XmlLibraryFactory extends BaseXmlLibraryFactory
{
    protected function getLibraryDependencies(\SimpleXMLElement $data)
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            [
                'eras' => $this->buildClassInstanceFromNodeAttributes($data, '//books/era', 'era'),
            ]
        );
    }

    protected function getBooksDependencies(\SimpleXMLElement $data, \SimpleXMLElement $book)
    {
        $era = $book->xpath('parent::era');

        return array_merge(
            parent::getBooksDependencies($data, $book),
            [
                'chronologicalMarker' => new $this->classes['chronologicalMarker'](
                    [
                        'timeStart' => (string) $book->{'time'},
                        'era' => new $this->classes['era']($this->getSimpleXmlElementAttributesAsArray(reset($era))),
                    ]
                ),
            ]
        );
    }
}
