<?php

namespace Gnutix\StarWarsLibrary\Twig\Extension;

/**
 * Star Wars Twig Extension
 */
class StarWarsExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('starWarsDate', array($this, 'transformToStarWarsDate')),
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
     * @return array
     */
    protected function getStarWarsDateSuffixes()
    {
        return array(
            'BBY' => '&nbsp;<abbr title="Before the Battle of Yavin IV">BBY</abbr>',
            'ABY' => '&nbsp;<abbr title="After the Battle of Yavin IV">ABY</abbr>',
        );
    }

    /**
     * @param string|int $date
     *
     * @return string
     */
    public function transformToStarWarsDate($date)
    {
        $date = trim((string) $date);
        $suffixes = $this->getStarWarsDateSuffixes();

        // Replace spaces between numbers by unbreakable spaces
        $date = preg_replace('#(\d+) (\d+)#', '$1&nbsp;$2', $date);

        // For dates with a format "140" or "-3590"
        if (preg_match('#^\-?\d+$#', $date)) {
            if ('-' === $date{0}) {
                return substr($date, 1).$suffixes['BBY'];
            }

            return $date.$suffixes['ABY'];
        }

        // For dates already having BBY/ABY
        if (preg_match('# (?:A|B)BY#', $date)) {

            // Replace any minus before a number
            $date = preg_replace('#\-(\d+)#', '$1', $date);

            // Replace the suffixes
            return preg_replace_callback(
                '# ((?:A|B)BY)#',
                function ($matches) use ($suffixes) {
                    return $suffixes[$matches[1]];
                },
                $date
            );
        }

        return $date;
    }
}
