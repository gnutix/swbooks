<?php

namespace Gnutix\Library\Twig\Extension;

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
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gnutix_library_extension';
    }
}
