<?php

namespace Gnutix\Library\LibraryFactory;

use Gnutix\Library\Dumper\YamlLibraryDumper;
use Gnutix\Library\LibraryFactoryInterface;
use Gnutix\Library\Loader\XmlFileLoader;

/**
 * Library Factory for the XML data
 */
final class XmlLibraryFactory implements LibraryFactoryInterface
{
    /** @var array */
    private $classes;

    /** @var \Gnutix\Library\LibraryInterface */
    private $library;

    /**
     * @param array                                $classes
     */
    public function __construct(XmlFileLoader $loader, $classes)
    {
        $this->classes = $classes;
        $this->library = new $this->classes['library']($this->getLibraryDependencies($loader->getData()));
    }

    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @return \Gnutix\Library\Dumper\YamlLibraryDumper
     */
    public function getLibraryDumper()
    {
        return new YamlLibraryDumper();
    }

    /**
     * @return array
     */
    private function getLibraryDependencies(\SimpleXMLElement $data)
    {
        return [
            'books' => $this->buildBooks($data),
            'categories' => $this->buildClassInstanceFromNodeAttributes(
                $data,
                '//information/types/type',
                'category',
                ['code' => 'id']
            ),
            'editors' => $this->buildClassInstanceFromNodeAttributes(
                $data,
                '//information/editors/editor',
                'editor',
                ['code' => 'id', 'lang' => 'preferredLanguage']
            ),
        ];
    }

    /**
     * @return array
     */
    private function renameArrayKeys(array $data, array $keys)
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
    private function buildClassInstanceFromNodeAttributes(
        \SimpleXMLElement $data,
        $xpathSelector,
        $targetClass,
        array $renameKeys = []
    ) {
        $editors = [];
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
     * @return array
     */
    private function getSimpleXmlElementAttributesAsArray(?\SimpleXMLElement $xmlElement = null)
    {
        if (null === $xmlElement) {
            return [];
        }

        $attributes = (array) $xmlElement->attributes();

        return isset($attributes['@attributes']) ? $attributes['@attributes'] : [];
    }

    /**
     * @return array
     */
    private function buildBooks(\SimpleXMLElement $data)
    {
        $books = [];

        foreach ($data->xpath('//books/era/book') as $book) {
            $books[] = new $this->classes['book']($this->getBooksDependencies($data, $book));
        }

        return $books;
    }

    /**
     * @return array
     */
    private function getBooksDependencies(\SimpleXMLElement $data, \SimpleXMLElement $book)
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
        $releases = [];
        foreach (array_keys($releaseEditorAttributes) as $release) {
            $releaseNode = $book->{$release};

            // Categories data
            $editor = $data->xpath('//information/editors/editor[@code="'.$releaseEditorAttributes[$release].'"]');
            $editorAttributes = $this->getSimpleXmlElementAttributesAsArray(reset($editor));

            $releases[] = new $this->classes['release'](
                [
                    'title' => (string) $book->{'title'},
                    'editor' => new $this->classes['editor'](
                        $this->renameArrayKeys($editorAttributes, ['code' => 'id', 'lang' => 'preferredLanguage'])
                    ),
                    'publicationDate' => isset($publishAttributes[$release])
                        ? $this->transformToDateTime($publishAttributes[$release])
                        : null,
                    'language' => new $this->classes['language'](
                        [
                            'id' => $editorAttributes['lang'],
                            'name' => $editorAttributes['lang'],
                        ]
                    ),
                    'owner' => new $this->classes['owner'](
                        [
                            'nbCopies' => isset($releaseNode['copies']) ? (int) $releaseNode['copies'] : null,
                            'nbReadings' => isset($releaseNode['readings']) ? (int) $releaseNode['readings'] : null,
                        ]
                    ),
                    'format' => new $this->classes['format'](),
                    'series' => new $this->classes['series'](
                        null !== $series
                            ? [
                                'title' => (string) $series,
                                'bookId' => isset($seriesAttributes['number']) ? $seriesAttributes['number'] : null,
                            ]
                            : []
                    ),
                ]
            );
        }

        $authors = [];
        foreach (explode(',', (string) $book->{'author'}) as $author) {
            $authors[] = new $this->classes['author'](['name' => trim($author)]);
        }

        return [
            'category' => new $this->classes['category'](
                [
                    'id' => $bookAttributes['type'],
                    'name' => $categoryAttributes['name'],
                ]
            ),
            'authors' => $authors,
            'releases' => $releases,
        ];
    }

    /**
     * @param string $date
     *
     * @return \DateTime
     */
    private function transformToDateTime($date)
    {
        $explodedDate = explode('.', $date);
        $dateTime = new \DateTime();

        return $dateTime->setDate($explodedDate[2], $explodedDate[1], $explodedDate[0]);
    }
}
