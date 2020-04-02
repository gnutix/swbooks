<?php

namespace Gnutix\Library\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Library Twig Extension
 */
final class LibraryExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('highlightChars', [$this, 'highlightChars'], ['is_safe' => ['html']]),
        ];
    }

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
