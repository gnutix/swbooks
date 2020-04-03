<?php

declare(strict_types=1);

namespace Gnutix\Library\Dumper;

use Gnutix\Library\LibraryDumperInterface;
use Gnutix\Library\LibraryInterface;
use Gnutix\Library\Model\Book;
use Symfony\Component\Yaml\Dumper;

class YamlLibraryDumper implements LibraryDumperInterface
{
    public function dump(LibraryInterface $library): string
    {
        $dumper = new Dumper();
        $dump = $dumper->dump($this->buildArray($library), 99);

        // Apply fixes on the dump output
        return (string) preg_replace(
            ["#^( +?-)(?:\n)( +?id: )'(&[a-zA-Z_]+)/([a-zA-Z_]+)'$#m", "#''#", "#'#", '#§#', '#null#'],
            ['$1 $3'.PHP_EOL.'$2$4', '§', '', "'", '~'],
            $dump
        );
    }

    protected function buildArray(LibraryInterface $library): array
    {
        $array = [
            'languages' => [],
            'formats' => [
                [
                    'id' => '&f_unknown/unknown',
                    'name' => 'Unknown',
                ],
            ],
            'categories' => [],
            'editors' => [],
            'authors' => [],
            'books' => [],
        ];

        foreach ($library->getCategories() as $category) {
            $array['categories'][] = [
                'id' => '&c_'.$category->getId().'/'.$category->getId(),
                'name' => $category->getName(),
            ];
        }
        foreach ($library->getEditors() as $editor) {
            $array['editors'][] = [
                'id' => '&e_'.$editor->getId().'/'.$editor->getId(),
                'name' => $editor->getName(),
                'preferredLanguage' => $editor->getPreferredLanguage(),
            ];
        }

        foreach ($library->getBooks() as $book) {
            $array['books'][] = $this->buildBookArray($book);

            foreach ($book->getAuthors() as $author) {
                $skip = false;

                foreach ($array['authors'] as $authorArray) {
                    if (isset($authorArray['name']) && $authorArray['name'] === $author->getName()) {
                        $skip = true;

                        break;
                    }
                }

                if ($skip) {
                    continue;
                }

                $array['authors'][] = [
                    'id' => '&a_'.$author->getId().'/'.$author->getId(),
                    'name' => $author->getName(),
                ];
            }

            foreach ($book->getReleases() as $release) {
                if (isset($array['languages'][$release->getLanguage()->getId()])) {
                    continue;
                }
                $array['languages'][$release->getLanguage()->getId()] = [
                    'id' => '&l_'.$release->getLanguage()->getId().'/'.$release->getLanguage()->getId(),
                    'name' => $release->getLanguage()->getName(),
                ];
            }
        }

        $array['languages'] = array_values($array['languages']);

        return $array;
    }

    protected function buildBookArray(Book $book): array
    {
        $bookArray = [
            'authors' => [],
            'category' => '*c_'.$book->getCategory()->getId(),
            'releases' => [],
        ];

        foreach ($book->getAuthors() as $author) {
            $bookArray['authors'][] = '*a_'.$author->getId();
        }

        foreach ($book->getReleases() as $release) {
            $publicationDate = $release->getPublicationDate();

            if ($publicationDate instanceof \DateTime) {
                $publicationDate = [
                    'day' => $publicationDate->format('d'),
                    'month' => $publicationDate->format('m'),
                    'year' => $publicationDate->format('Y'),
                ];
            }

            $bookArray['releases'][] = [
                'title' => $release->getTitle(),
                'language' => '*l_'.$release->getLanguage()->getId(),
                'publicationDate' => $publicationDate,
                'editor' => '*e_'.$release->getEditor()->getId(),
                'format' => '*f_unknown',
                'owner' => [
                    'copies' => $release->getOwner()->getNbCopies(),
                    'readings' => $release->getOwner()->getNbReadings(),
                ],
                'series' => [
                    'id' => $release->getSeries()->getId(),
                    'title' => $release->getSeries()->getTitle(),
                    'number' => $release->getSeries()->getBookId(),
                ],
            ];
        }

        return $bookArray;
    }
}
