<?php

namespace Gnutix\StarWarsLibrary\Twig\Extension;

/**
 * Gnutix Library Twig Extension
 */
class GnutixStarWarsLibraryExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('starWarsDate', array($this, 'starWarsDate')),
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
     * @param int $date
     *
     * @return string
     */
    public function starWarsDate($date)
    {
        $date = (string) $date;

        if (empty($date) || false !== strpos($date, 'BY')) {
            return $date;
        }

        if ('-' === substr($date, 0, 1)) {
            return substr($date, 1).' <abbr title="Before the Battle of Yavin IV">BBY</abbr>';
        }

        return $date.' <abbr title="After the Battle of Yavin IV">ABY</abbr>';
    }
}
