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
    protected function getClassesNames()
    {
        return array_merge(
            parent::getClassesNames(),
            array(
                'library' => '\Gnutix\StarWarsLibrary\Model\Library',
                'book' => '\Gnutix\StarWarsLibrary\Model\Book',
                'era' => '\Gnutix\StarWarsLibrary\Model\Era',
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function buildLibrary(\SimpleXMLElement $data)
    {
        $this->library = new $this->classes['library'](
            $this->buildBooks($data),
            $this->buildCategories($data),
            $this->buildEditors($data),
            $this->buildEras($data)
        );
    }

    /**
     * @param \SimpleXMLElement $data
     *
     * @return array
     */
    protected function buildEras(\SimpleXMLElement $data)
    {
        $eras = array();

        foreach ($data->xpath('//books/era') as $element) {
            $eras[] = new $this->classes['era']($this->getSimpleXmlElementAttributesAsArray($element));
        }

        return $eras;
    }
}
