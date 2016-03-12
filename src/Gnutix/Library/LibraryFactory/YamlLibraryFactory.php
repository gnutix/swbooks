<?php

namespace Gnutix\Library\LibraryFactory;

use Gnutix\Library\Dumper\YamlLibraryDumper;
use Gnutix\Library\Loader\YamlFileLoader;
use Gnutix\Library\LibraryFactoryInterface;

/**
 * Library Factory for the YAML data
 */
class YamlLibraryFactory implements LibraryFactoryInterface
{
    /** @var array */
    protected $classes;

    /** @var \Gnutix\Library\LibraryInterface */
    protected $library;

    /**
     * @param \Gnutix\Library\Loader\YamlFileLoader $loader
     */
    public function __construct(YamlFileLoader $loader)
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
     * @param array $data
     *
     * @return array
     */
    protected function getLibraryDependencies(array $data)
    {
        return array(
            'books' => $this->buildBooks($this->get($data, 'books', array())),
            'categories' => $this->buildClassInstanceFromArray($this->get($data, 'categories', array()), 'category'),
            'editors' => $this->buildClassInstanceFromArray($this->get($data, 'editors', array()), 'editor'),
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
     * @param array  $data        The XML data
     * @param string $targetClass The target for the class
     * @param array  $renameKeys  The rename keys array
     *
     * @return array
     */
    protected function buildClassInstanceFromArray(
        array $data,
        $targetClass,
        array $renameKeys = array()
    ) {
        $elements = array();
        $className = $this->classes[$targetClass];

        foreach ($data as $element) {
            $elements[] = new $className($this->renameArrayKeys($element, $renameKeys));
        }

        return $elements;
    }

    /**
     * @param array $books
     *
     * @return array
     */
    protected function buildBooks(array $books)
    {
        $booksObjects = array();

        foreach ($books as $book) {
            $booksObjects[] = new $this->classes['book']($this->getBookDependencies($book));
        }

        return $booksObjects;
    }

    /**
     * @param array $book
     *
     * @return array
     */
    protected function getBookDependencies(array $book)
    {
        return array(
            'category' => new $this->classes['category']($this->get($book, 'category', array())),
            'authors' => $this->buildAuthors($book),
            'releases' => $this->buildReleases($this->get($book, 'releases', array())),
        );
    }

    /**
     * @param array $book
     *
     * @return array
     */
    protected function buildAuthors(array $book)
    {
        $authorsObjects = array();

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
     * @param array $releases
     *
     * @return array
     */
    protected function buildReleases(array $releases)
    {
        $releasesObjects = array();

        foreach ($releases as $release) {
            $releasesObjects[] = new $this->classes['release']($this->buildReleaseDependencies($release));
        }

        return $releasesObjects;
    }

    /**
     * @param array $release
     *
     * @return array
     */
    protected function buildReleaseDependencies(array $release)
    {
        $that = $this;

        return array(
            'title' => $this->get($release, 'title'),
            'language' => new $this->classes['language']($this->get($release, 'language', array())),
            'editor' => new $this->classes['editor']($this->get($release, 'editor', array())),
            'format' => new $this->classes['format']($this->get($release, 'format', array())),
            'publicationDate' => $this->get(
                $release,
                'publicationDate',
                null,
                function ($data) use ($that) {
                    return $that->transformToDateTime($data);
                }
            ),
            'series' => new $this->classes['series'](
                $this->get(
                    $release,
                    'series',
                    array(),
                    function ($data) use ($that) {
                        return $that->renameArrayKeys($data, array('number' => 'bookId'));
                    }
                )
            ),
            'owner' => new $this->classes['owner'](
                $this->get(
                    $release,
                    'owner',
                    array(),
                    function ($data) use ($that) {
                        return $that->renameArrayKeys($data, array('copies' => 'nbCopies', 'readings' => 'nbReadings'));
                    }
                )
            ),
        );
    }

    /**
     * @param array $date
     *
     * @return \DateTime
     */
    public function transformToDateTime(array $date)
    {
        foreach (array('day', 'month', 'year') as $key) {
            if (!isset($date[$key])) {
                $date[$key] = 0;
            }
        }

        $dateTime = new \DateTime();

        return $dateTime->setDate($date['year'], $date['month'], $date['day']);
    }

    /**
     * @param array $data
     * @param array $keys
     *
     * @return array
     */
    public function renameArrayKeys(array $data, array $keys)
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

    /**
     * @param array    $data
     * @param string   $index
     * @param mixed    $default
     * @param \Closure $callback
     * @param bool     $throwException
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function get(array $data, $index, $default = null, \Closure $callback = null, $throwException = false)
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
}
