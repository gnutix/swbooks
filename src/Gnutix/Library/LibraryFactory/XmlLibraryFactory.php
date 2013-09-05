<?php

namespace Gnutix\Library\LibraryFactory;

use Gnutix\Library\Loader\XmlFileLoader;
use Gnutix\Library\LibraryFactoryInterface;

/**
 * Library Factory for the XML data
 */
class XmlLibraryFactory implements LibraryFactoryInterface
{
    /** @var array */
    protected $classes;

    /** @var \Gnutix\Library\LibraryInterface */
    protected $library;

    /**
     * @param \Gnutix\Library\Loader\XmlFileLoader $loader
     */
    public function __construct(XmlFileLoader $loader)
    {
        $this->classes = $this->getClassesNames();
        $this->buildLibrary($loader->getData());
    }

    /**
     * {@inheritDoc}
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @return array
     */
    protected function getClassesNames()
    {
        return array(
            'library' => '\Gnutix\Library\Model\Library',
            'book' => '\Gnutix\Library\Model\Book',
            'editor' => '\Gnutix\Library\Model\Editor',
            'category' => '\Gnutix\Library\Model\Category',
        );
    }

    /**
     * @param \SimpleXMLElement $data
     * @throws \UnexpectedValueException
     */
    protected function buildLibrary(\SimpleXMLElement $data)
    {
        $this->library = new $this->classes['library'](
            $this->buildBooks($data),
            $this->buildCategories($data),
            $this->buildEditors($data)
        );
    }

    /**
     * @param \SimpleXMLElement $data
     *
     * @return array
     */
    protected function buildBooks(\SimpleXMLElement $data)
    {
        $books = array();

        foreach ($data->xpath('//information/editors/editor') as $element) {
            $books[] = new $this->classes['book']($this->getSimpleXmlElementAttributesAsArray($element));
        }

        return $books;
    }

    /**
     * @param \SimpleXMLElement $data
     *
     * @return array
     */
    protected function buildCategories(\SimpleXMLElement $data)
    {
        $categories = array();

        foreach ($data->xpath('//information/types/type') as $element) {
            $categories[] = new $this->classes['category']($this->getSimpleXmlElementAttributesAsArray($element));
        }

        return $categories;
    }

    /**
     * @param \SimpleXMLElement $data
     *
     * @return array
     */
    protected function buildEditors(\SimpleXMLElement $data)
    {
        $editors = array();

        foreach ($data->xpath('//information/editors/editor') as $element) {
            $editors[] = new $this->classes['editor']($this->getSimpleXmlElementAttributesAsArray($element));
        }

        return $editors;
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     *
     * @return array
     */
    protected function getSimpleXmlElementAttributesAsArray(\SimpleXMLElement $xmlElement)
    {
        $attributes = (array) $xmlElement->attributes();

        return isset($attributes['@attributes']) ? $attributes['@attributes'] : array();
    }
}
