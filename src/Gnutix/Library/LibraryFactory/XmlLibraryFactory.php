<?php

namespace Gnutix\Library\LibraryFactory;

use Gnutix\Library\Dumper\YamlLibraryDumper;
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
        $this->classes = $this->getClassesMap();
        $this->library = new $this->classes['library']($this->getLibraryDependencies($loader->getData()));
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
    protected function getClassesMap()
    {
        return array(
            'author' => '\Gnutix\Library\Model\Author',
            'book' => '\Gnutix\Library\Model\Book',
            'category' => '\Gnutix\Library\Model\Category',
            'editor' => '\Gnutix\Library\Model\Editor',
            'language' => '\Gnutix\Library\Model\Language',
            'library' => '\Gnutix\Library\Model\Library',
            'release' => '\Gnutix\Library\Model\Release',
            'series' => '\Gnutix\Library\Model\Series',
            'format' => '\Gnutix\Library\Model\Format',
            'owner' => '\Gnutix\Library\Model\Owner',
        );
    }

    /**
     * @param \SimpleXMLElement $data
     *
     * @return array
     */
    protected function getLibraryDependencies(\SimpleXMLElement $data)
    {
        return array(
            'books' => $this->buildBooks($data),
            'categories' => $this->buildClassInstanceFromNodeAttributes(
                $data,
                '//information/types/type',
                'category',
                array('code' => 'id')
            ),
            'editors' => $this->buildClassInstanceFromNodeAttributes(
                $data,
                '//information/editors/editor',
                'editor',
                array('code' => 'id', 'lang' => 'preferredLanguage')
            ),
        );
    }

    /**
     * @return \Gnutix\Library\Dumper\YamlLibraryDumper
     */
    public function getLibraryDumper()
    {
        return new YamlLibraryDumper();
    }

    /**
     * @param array $data
     * @param array $keys
     *
     * @return array
     */
    protected function renameArrayKeys(array $data, array $keys)
    {
        foreach ($keys as $old => $new) {
            if (!isset($data[$old])) {
                continue;
            }

            $data[$new] = $data[$old];
            unset($data[$old]);
        }

        return $data;
    }

    /**
     * @param \SimpleXMLElement $data          The XML data
     * @param string            $xpathSelector The XPath selector
     * @param string            $targetClass   The target for the class
     * @param array             $renameKeys    The rename keys array
     *
     * @return array
     */
    protected function buildClassInstanceFromNodeAttributes(
        \SimpleXMLElement $data,
        $xpathSelector,
        $targetClass,
        array $renameKeys = array()
    ) {
        $editors = array();
        $className = $this->classes[$targetClass];

        foreach ($data->xpath($xpathSelector) as $element) {
            $dependencies = $this->getSimpleXmlElementAttributesAsArray($element);

            if (!empty($renameKeys)) {
                $dependencies = $this->renameArrayKeys($dependencies, $renameKeys);
            }

            $editors[] = new $className($dependencies);
        }

        return $editors;
    }

    /**
     * @param \SimpleXMLElement|null $xmlElement
     *
     * @return array
     */
    protected function getSimpleXmlElementAttributesAsArray(\SimpleXMLElement $xmlElement = null)
    {
        if (null === $xmlElement) {
            return array();
        }

        $attributes = (array) $xmlElement->attributes();

        return isset($attributes['@attributes']) ? $attributes['@attributes'] : array();
    }

    /**
     * @param \SimpleXMLElement $data
     *
     * @return array
     */
    protected function buildBooks(\SimpleXMLElement $data)
    {
        $books = array();

        foreach ($data->xpath('//books/era/book') as $book) {
            $books[] = new $this->classes['book']($this->getBooksDependencies($data, $book));
        }

        return $books;
    }

    /**
     * @param \SimpleXMLElement $data
     * @param \SimpleXMLElement $book
     *
     * @return array
     */
    protected function getBooksDependencies(\SimpleXMLElement $data, \SimpleXMLElement $book)
    {
        // Book attributes
        $bookAttributes = $this->getSimpleXmlElementAttributesAsArray($book);

        // Series attributes
        $series = $book->{'series'}[0];
        $seriesAttributes = $this->getSimpleXmlElementAttributesAsArray($series);

        // Publish attributes
        $publish = $book->{'publish'}[0];
        $publishAttributes = $this->getSimpleXmlElementAttributesAsArray($publish);

        // Editor attributes
        $releaseEditor = $book->{'editor'}[0];
        $releaseEditorAttributes = $this->getSimpleXmlElementAttributesAsArray($releaseEditor);

        // Categories data
        $categories = $data->xpath('//information/types/type[@code="'.$bookAttributes['type'].'"]');
        $categoryAttributes = $this->getSimpleXmlElementAttributesAsArray(reset($categories));

        // Releases data
        $releases = array();
        foreach (array_keys($releaseEditorAttributes) as $release) {
            $releaseNode = $book->$release;

            // Categories data
            $editor = $data->xpath('//information/editors/editor[@code="'.$releaseEditorAttributes[$release].'"]');
            $editorAttributes = $this->getSimpleXmlElementAttributesAsArray(reset($editor));

            $releases[] = new $this->classes['release'](
                array(
                    'title' => (string) $book->{'title'},
                    'editor' => new $this->classes['editor'](
                        $this->renameArrayKeys(
                            $editorAttributes,
                            array('code' => 'id', 'lang' => 'preferredLanguage')
                        )
                    ),
                    'publicationDate' => isset($publishAttributes[$release])
                        ? $this->transformToDateTime($publishAttributes[$release])
                        : null,
                    'language' => new $this->classes['language'](
                        array(
                            'id' => $editorAttributes['lang'],
                            'name' => $editorAttributes['lang'],
                        )
                    ),
                    'owner' => new $this->classes['owner'](
                        array(
                            'nbCopies' => isset($releaseNode['copies']) ? (int) $releaseNode['copies'] : null,
                            'nbReadings' => isset($releaseNode['readings']) ? (int) $releaseNode['readings'] : null,
                        )
                    ),
                    'format' => new $this->classes['format'](),
                    'series' => new $this->classes['series'](
                        null !== $series
                            ? array(
                                'title' => (string) $series,
                                'bookId' => isset($seriesAttributes['number']) ? $seriesAttributes['number'] : null,
                            )
                            : array()
                    ),
                )
            );
        }

        $authors = array();
        foreach (explode(',', (string) $book->{'author'}) as $author) {
            $authors[] = new $this->classes['author'](array('name' => trim($author)));
        }

        return array(
            'category' => new $this->classes['category'](
                array(
                    'id' => $bookAttributes['type'],
                    'name' => $categoryAttributes['name'],
                )
            ),
            'authors' => $authors,
            'releases' => $releases,
        );
    }

    /**
     * @param string $date
     *
     * @return \DateTime
     */
    protected function transformToDateTime($date)
    {
        $explodedDate = explode('.', $date);
        $dateTime = new \DateTime();

        return $dateTime->setDate($explodedDate[2], $explodedDate[1], $explodedDate[0]);
    }
}
