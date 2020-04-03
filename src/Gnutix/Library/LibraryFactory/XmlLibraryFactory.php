<?php

declare(strict_types=1);

namespace Gnutix\Library\LibraryFactory;

use DateTime;
use Gnutix\Library\Dumper\YamlLibraryDumper;
use Gnutix\Library\LibraryFactoryInterface;
use Gnutix\Library\LibraryInterface;
use Gnutix\Library\Loader\XmlFileLoader;
use SimpleXMLElement;
use Webmozart\Assert\Assert;

class XmlLibraryFactory implements LibraryFactoryInterface
{
    protected array $classes;
    private LibraryInterface $library;

    public function __construct(XmlFileLoader $loader, array $classes)
    {
        $this->classes = $classes;
        $this->library = new $this->classes['library']($this->getLibraryDependencies($loader->getData()));
    }

    public function getLibrary(): LibraryInterface
    {
        return $this->library;
    }

    public function getLibraryDumper(): YamlLibraryDumper
    {
        return new YamlLibraryDumper();
    }

    protected function getLibraryDependencies(SimpleXMLElement $data): array
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

    protected function renameArrayKeys(array $data, array $keys): array
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

    protected function buildClassInstanceFromNodeAttributes(
        SimpleXMLElement $data,
        string $xpathSelector,
        string $targetClass,
        array $renameKeys = []
    ): array {
        $editors = [];
        $className = $this->classes[$targetClass];
        $elements = $data->xpath($xpathSelector);
        Assert::isIterable($elements);

        foreach ($elements as $element) {
            $dependencies = $this->getSimpleXmlElementAttributesAsArray($element);

            if (!empty($renameKeys)) {
                $dependencies = $this->renameArrayKeys($dependencies, $renameKeys);
            }

            $editors[] = new $className($dependencies);
        }

        return $editors;
    }

    protected function getSimpleXmlElementAttributesAsArray(?SimpleXMLElement $xmlElement = null): array
    {
        if (null === $xmlElement) {
            return [];
        }

        $attributes = (array) $xmlElement->attributes();

        return $attributes['@attributes'] ?? [];
    }

    protected function buildBooks(SimpleXMLElement $data): array
    {
        $books = [];
        $elements = $data->xpath('//books/era/book');
        Assert::isIterable($elements);

        foreach ($elements as $book) {
            $books[] = new $this->classes['book']($this->getBooksDependencies($data, $book));
        }

        return $books;
    }

    protected function getBooksDependencies(SimpleXMLElement $data, SimpleXMLElement $book): array
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
        Assert::isIterable($categories);
        $category = reset($categories);
        Assert::isInstanceOf($category, SimpleXMLElement::class);
        $categoryAttributes = $this->getSimpleXmlElementAttributesAsArray($category);

        // Releases data
        $releases = [];
        foreach (array_keys($releaseEditorAttributes) as $release) {
            $releaseNode = $book->{$release};

            // Categories data
            $editors = $data->xpath('//information/editors/editor[@code="'.$releaseEditorAttributes[$release].'"]');
            Assert::isIterable($editors);
            $editor = reset($editors);
            Assert::isInstanceOf($editor, SimpleXMLElement::class);
            $editorAttributes = $this->getSimpleXmlElementAttributesAsArray($editor);

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
                                'bookId' => $seriesAttributes['number'] ?? null,
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

    private function transformToDateTime(string $date): DateTime
    {
        $explodedDate = explode('.', $date);
        $dateTime = new DateTime();

        return $dateTime->setDate((int) $explodedDate[2], (int) $explodedDate[1], (int) $explodedDate[0]);
    }
}
