<?php

namespace Gnutix\Library\LibraryFactory;

use Gnutix\Library\Dumper\YamlLibraryDumper;
use Gnutix\Library\LibraryFactoryInterface;
use Gnutix\Library\Loader\YamlFileLoader;

/**
 * Library Factory for the YAML data
 */
class YamlLibraryFactory implements LibraryFactoryInterface
{
    /** @var array */
    protected $classes;

    /** @var \Gnutix\Library\LibraryInterface */
    private $library;

    /**
     * @param array                                 $classes
     */
    public function __construct(YamlFileLoader $loader, $classes)
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

    protected function getClass(string $class)
    {
        return $this->classes[$class];
    }

    /**
     * @return array
     */
    protected function getLibraryDependencies(array $data)
    {
        return [
            'books' => $this->buildBooks($this->get($data, 'books', [])),
            'categories' => $this->buildClassInstanceFromArray($this->get($data, 'categories', []), 'category'),
            'editors' => $this->buildClassInstanceFromArray($this->get($data, 'editors', []), 'editor'),
        ];
    }

    /**
     * @param array  $data        The XML data
     * @param string $targetClass The target for the class
     * @param array  $renameKeys  The rename keys array
     *
     * @return array
     */
    protected function buildClassInstanceFromArray(array $data, $targetClass, array $renameKeys = [])
    {
        $elements = [];
        $className = $this->getClass($targetClass);

        foreach ($data as $element) {
            $elements[] = new $className($this->renameArrayKeys($element, $renameKeys));
        }

        return $elements;
    }

    /**
     * @return array
     */
    protected function buildBooks(array $books)
    {
        $booksObjects = [];

        foreach ($books as $book) {
            $booksObjects[] = new $this->classes['book']($this->getBookDependencies($book));
        }

        return $booksObjects;
    }

    /**
     * @return array
     */
    protected function getBookDependencies(array $book)
    {
        return [
            'category' => new $this->classes['category']($this->get($book, 'category', [])),
            'authors' => $this->buildAuthors($book),
            'releases' => $this->buildReleases($this->get($book, 'releases', [])),
        ];
    }

    /**
     * @return array
     */
    protected function buildAuthors(array $book)
    {
        $authorsObjects = [];

        if (null !== $author = $this->get($book, 'author')) {
            $book['authors'][] = $author;
            unset($book['author']);
        }

        foreach ($this->get($book, 'authors') as $author) {
            $authorsObjects[] = new $this->classes['author']($author);
        }

        return $authorsObjects;
    }

    /**
     * @return array
     */
    protected function buildReleases(array $releases)
    {
        $releasesObjects = [];

        foreach ($releases as $release) {
            $releasesObjects[] = new $this->classes['release']($this->buildReleaseDependencies($release));
        }

        return $releasesObjects;
    }

    /**
     * @return array
     */
    protected function buildReleaseDependencies(array $release)
    {
        return [
            'title' => $this->get($release, 'title'),
            'language' => new $this->classes['language']($this->get($release, 'language', [])),
            'editor' => new $this->classes['editor']($this->get($release, 'editor', [])),
            'format' => new $this->classes['format']($this->get($release, 'format', [])),
            'publicationDate' => $this->get(
                $release,
                'publicationDate',
                null,
                function ($data) {
                    return $this->transformToDateTime($data);
                }
            ),
            'series' => new $this->classes['series'](
                $this->get(
                    $release,
                    'series',
                    [],
                    function ($data) {
                        return $this->renameArrayKeys($data, ['number' => 'bookId']);
                    }
                )
            ),
            'owner' => new $this->classes['owner'](
                $this->get(
                    $release,
                    'owner',
                    [],
                    function ($data) {
                        return $this->renameArrayKeys($data, ['copies' => 'nbCopies', 'readings' => 'nbReadings']);
                    }
                )
            ),
        ];
    }

    /**
     * @param string   $index
     * @param bool     $throwException
     *
     * @throws \InvalidArgumentException
     */
    protected function get(array $data, $index, $default = null, ?callable $callback = null, $throwException = false)
    {
        if (isset($data[$index])) {
            if (is_callable($callback)) {
                return $callback($data[$index]);
            }

            return $data[$index];
        }

        if (!$throwException) {
            return $default;
        }

        throw new \InvalidArgumentException('Index "'.$index.'" could not be found.');
    }

    protected function transformToDateTime(array $date): \DateTime
    {
        foreach (['day', 'month', 'year'] as $key) {
            if (!isset($date[$key])) {
                $date[$key] = 0;
            }
        }

        $dateTime = new \DateTime();

        return $dateTime->setDate($date['year'], $date['month'], $date['day']);
    }

    protected function renameArrayKeys(array $data, array $keys): array
    {
        foreach ($keys as $old => $new) {
            // PS: do not use isset as the value may be null
            if (!array_key_exists($old, $data)) {
                continue;
            }

            $data[$new] = $data[$old];
            unset($data[$old]);
        }

        return $data;
    }
}
