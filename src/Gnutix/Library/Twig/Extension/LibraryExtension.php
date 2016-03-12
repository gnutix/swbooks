<?php

namespace Gnutix\Library\Twig\Extension;

/**
 * Library Twig Extension
 */
class LibraryExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('highlightChars', array($this, 'highlightChars'), array('is_safe' => array('html'))),
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
     * @param string $source
     * @param string $search
     *
     * @return string
     */
    public function highlightChars($source, $search)
    {
        $characters = str_split($source);
        $search = str_split($search);

        $string = '';
        foreach ($characters as $character) {
            $originalCharacter = $character;
            $character = strtolower($character);
            if (in_array($character, $search, true)) {
                $string .= '<strong>'.$originalCharacter.'</strong>';
                unset($search[array_search($character, $search, true)]);
                continue;
            }
            $string .= $originalCharacter;
        }

        return $string;
    }
}
