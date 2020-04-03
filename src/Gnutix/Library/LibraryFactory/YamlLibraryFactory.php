<?php

declare(strict_types=1);

namespace Gnutix\Library\LibraryFactory;

use DateTime;
use Gnutix\Library\Dumper\YamlLibraryDumper;
use Gnutix\Library\LibraryFactoryInterface;
use Gnutix\Library\LibraryInterface;
use Gnutix\Library\Loader\YamlFileLoader;

class YamlLibraryFactory implements LibraryFactoryInterface
{
    protected array $classes;
    private LibraryInterface $library;

    public function __construct(YamlFileLoader $loader, array $classes)
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

    protected function getLibraryDependencies(array $data): array
    {
        return [
            'books' => $this->buildBooks($this->get($data, 'books', [])),
            'categories' => $this->buildClassInstanceFromArray($this->get($data, 'categories', []), 'category'),
            'editors' => $this->buildClassInstanceFromArray($this->get($data, 'editors', []), 'editor'),
        ];
    }

    protected function buildClassInstanceFromArray(array $data, string $targetClass, array $renameKeys = []): array
    {
        $elements = [];
        $className = $this->classes[$targetClass];

        foreach ($data as $element) {
            $elements[] = new $className($this->renameArrayKeys($element, $renameKeys));
        }

        return $elements;
    }

    protected function buildBooks(array $books): array
    {
        $booksObjects = [];

        foreach ($books as $book) {
            $booksObjects[] = new $this->classes['book']($this->getBookDependencies($book));
        }

        return $booksObjects;
    }

    protected function getBookDependencies(array $book): array
    {
        return [
            'category' => new $this->classes['category']($this->get($book, 'category', [])),
            'authors' => $this->buildAuthors($book),
            'releases' => $this->buildReleases($this->get($book, 'releases', [])),
        ];
    }

    protected function buildAuthors(array $book): array
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

    protected function buildReleases(array $releases): array
    {
        $releasesObjects = [];

        foreach ($releases as $release) {
            $releasesObjects[] = new $this->classes['release']($this->buildReleaseDependencies($release));
        }

        return $releasesObjects;
    }

    protected function buildReleaseDependencies(array $release): array
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
     * @param mixed|null $default
     *
     * @return mixed
     */
    protected function get(
        array $data,
        string $index,
        $default = null,
        ?callable $callback = null,
        bool $throwException = false
    ) {
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

    protected function transformToDateTime(array $date): DateTime
    {
        foreach (['day', 'month', 'year'] as $key) {
            if (!isset($date[$key])) {
                $date[$key] = 0;
            }
        }

        $dateTime = new DateTime();

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
