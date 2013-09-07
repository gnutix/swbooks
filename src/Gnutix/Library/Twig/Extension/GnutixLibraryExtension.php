<?php

namespace Gnutix\Library\Twig\Extension;

use Gnutix\Library\Model\Book;

/**
 * Gnutix Library Twig Extension
 */
class GnutixLibraryExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('deprecated_display_books_from_xml', 'displayBooksFromXml'),
            new \Twig_SimpleFunction('get_cell_rowspan', array($this, 'getCellRowspan')),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gnutix_library_extension';
    }

    /**
     * @param \Gnutix\Library\Model\Book[] $books
     * @param string                       $method
     * @param array                        $arguments
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     *
     * @deprecated
     */
    public function getCellRowspan(array $books, $method, array $arguments = array())
    {
        $rowspan = 1;

        foreach ($books as $book) {
            foreach ($book->getReleases() as $release) {

                // Ensure we're dealing with instances of Release object
                if (!($book instanceof Book)) {
                    throw new \InvalidArgumentException(
                        'The method "'.__METHOD__.'" accepts only Release[] as a first argument.'
                    );
                }

                // Apply some Twig magic logic for calling methods
                $methodNames = array($method, 'get'.ucfirst($method));

                // Ensure at least one of the method exist
                $nbMethodNames = count($methodNames);
                foreach ($methodNames as $index => $methodName) {
                    if (method_exists($book, $methodName)) {
                        $method = $methodName;
                        break;
                    }

                    // If it's the last loop, then we're in trouble
                    if ($nbMethodNames === ($index + 1)) {
                        throw new \BadMethodCallException(
                            'None of the following methods exists on object "'.get_class($book).'": '.
                            implode(', ', $methodNames).'.'
                        );
                    }
                }

                // Call the method on the object
                $value = call_user_func_array(array($book, $method), $arguments);

                // If there's been at least one loop and the value is the same as the last one
                if (isset($lastOccurrence) && $lastOccurrence === $value) {
                    ++$rowspan;
                }

                // Set the last occurrence for the next loop iteration
                $lastOccurrence = $value;
            }
        }

        return $rowspan;
    }
}
