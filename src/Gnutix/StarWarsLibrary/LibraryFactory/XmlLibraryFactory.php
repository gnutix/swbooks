<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\LibraryFactory;

use Gnutix\Library\LibraryFactory\XmlLibraryFactory as BaseXmlLibraryFactory;
use Gnutix\StarWarsLibrary\Model\Library;
use SimpleXMLElement;
use Webmozart\Assert\Assert;

/**
 * @method Library getLibrary()
 */
final class XmlLibraryFactory extends BaseXmlLibraryFactory
{
    protected function getLibraryDependencies(SimpleXMLElement $data): array
    {
        return array_merge(
            parent::getLibraryDependencies($data),
            [
                'eras' => $this->buildClassInstanceFromNodeAttributes($data, '//books/era', 'era'),
            ]
        );
    }

    protected function getBooksDependencies(SimpleXMLElement $data, SimpleXMLElement $book): array
    {
        $eras = $book->xpath('parent::era');
        Assert::isIterable($eras);
        $era = reset($eras);
        Assert::isInstanceOf($era, SimpleXMLElement::class);

        return array_merge(
            parent::getBooksDependencies($data, $book),
            [
                'chronologicalMarker' => new $this->classes['chronologicalMarker'](
                    [
                        'timeStart' => (string) $book->{'time'},
                        'era' => new $this->classes['era']($this->getSimpleXmlElementAttributesAsArray($era)),
                    ]
                ),
            ]
        );
    }
}
