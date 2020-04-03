<?php

declare(strict_types=1);

namespace Gnutix\Library\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class LibraryExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('highlightChars', [$this, 'highlightChars'], ['is_safe' => ['html']]),
        ];
    }

    public function getName(): string
    {
        return 'gnutix_library_extension';
    }

    public function highlightChars(string $source, string $search): string
    {
        $characters = str_split($source);
        $searchArray = str_split($search);

        $string = '';
        foreach ($characters as $character) {
            $originalCharacter = $character;
            $character = strtolower($character);
            if (in_array($character, $searchArray, true)) {
                $string .= '<strong>'.$originalCharacter.'</strong>';
                unset($searchArray[array_search($character, $searchArray, true)]);

                continue;
            }
            $string .= $originalCharacter;
        }

        return $string;
    }
}
