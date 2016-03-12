<?php

namespace Gnutix\Library\Dumper;

use Symfony\Component\Yaml\Dumper;
use Gnutix\Library\LibraryDumperInterface;
use Gnutix\Library\LibraryInterface;
use Gnutix\Library\Model\Book;

/**
 * YAML Library Dumper
 */
class YamlLibraryDumper implements LibraryDumperInterface
{
    /**
     * {@inheritDoc}
     */
    public function dump(LibraryInterface $library)
    {
        $dumper = new Dumper;
        $dump = $dumper->dump($this->buildArray($library), 99);

        // Apply fixes on the dump output
        $dump = preg_replace(
            array(
                "#^( +?-)(?:\n)( +?id: )'(&[a-zA-Z_]+)/([a-zA-Z_]+)'$#m",
                "#''#",
                "#'#",
                "#§#",
                "#null#",
            ),
            array(
                "$1 $3".PHP_EOL."$2$4",
                "§",
                "",
                "'",
                "~",
            ),
            $dump
        );

        return $dump;
    }

    /**
     * {@inheritDoc}
     */
    protected function buildArray(LibraryInterface $library)
    {
        $array = array(
            'languages' => array(),
            'formats' => array(
                array(
                    'id' => '&f_unknown/unknown',
                    'name' => 'Unknown',
                )
            ),
            'categories' => array(),
            'editors' => array(),
            'authors' => array(),
            'books' => array(),
        );

        foreach ($library->getCategories() as $category) {
            $array['categories'][] = array(
                'id' => '&c_'.$category->getId().'/'.$category->getId(),
                'name' => $category->getName(),
            );
        }
        foreach ($library->getEditors() as $editor) {
            $array['editors'][] = array(
                'id' => '&e_'.$editor->getId().'/'.$editor->getId(),
                'name' => $editor->getName(),
                'preferredLanguage' => $editor->getPreferredLanguage(),
            );
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

                $array['authors'][] = array(
                    'id' => '&a_'.$author->getId().'/'.$author->getId(),
                    'name' => $author->getName(),
                );
            }

            foreach ($book->getReleases() as $release) {
                if (isset($array['languages'][$release->getLanguage()->getId()])) {
                    continue;
                }
                $array['languages'][$release->getLanguage()->getId()] = array(
                    'id' => '&l_'.$release->getLanguage()->getId().'/'.$release->getLanguage()->getId(),
                    'name' => $release->getLanguage()->getName(),
                );
            }
        }

        $array['languages'] = array_values($array['languages']);

        return $array;
    }

    /**
     * @param \Gnutix\Library\Model\Book $book
     *
     * @return array
     */
    protected function buildBookArray(Book $book)
    {
        $bookArray = array(
            'authors' => array(),
            'category' => '*c_'.$book->getCategory()->getId(),
            'releases' => array(),
        );

        foreach ($book->getAuthors() as $author) {
            $bookArray['authors'][] = '*a_'.$author->getId();
        }

        foreach ($book->getReleases() as $release) {
            $publicationDate = $release->getPublicationDate();

            if ($publicationDate instanceof \DateTime) {
                $publicationDate = array(
                    'day' => $publicationDate->format('d'),
                    'month' => $publicationDate->format('m'),
                    'year' => $publicationDate->format('Y'),
                );
            }

            $bookArray['releases'][] = array(
                'title' => $release->getTitle(),
                'language' => '*l_'.$release->getLanguage()->getId(),
                'publicationDate' => $publicationDate,
                'editor' => '*e_'.$release->getEditor()->getId(),
                'format' => '*f_unknown',
                'owner' => array(
                    'copies' => $release->getOwner()->getNbCopies(),
                    'readings' => $release->getOwner()->getNbReadings(),
                ),
                'series' => array(
                    'id' => $release->getSeries()->getId(),
                    'title' => $release->getSeries()->getTitle(),
                    'number' => $release->getSeries()->getBookId(),
                ),
            );
        }

        return $bookArray;
    }
}
