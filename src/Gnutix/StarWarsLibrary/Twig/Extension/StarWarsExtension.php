<?php

declare(strict_types=1);

namespace Gnutix\StarWarsLibrary\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class StarWarsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('starWarsDate', [$this, 'transformToStarWarsDate'])];
    }

    public function getName(): string
    {
        return 'gnutix_star_wars_extension';
    }

    /**
     * @param float|string|null $dateInput
     */
    public function transformToStarWarsDate($dateInput): string
    {
        $date = trim((string) $dateInput);
        $suffixes = $this->getStarWarsDateSuffixes();

        // Replace spaces between numbers by unbreakable spaces
        $date = (string) preg_replace('#([0-9.]+) ([0-9.]+)#', '$1&nbsp;$2', $date);

        // For dates with a format "140" or "-3590"
        if (preg_match('#^-?(?:[0-9.]+)$#', $date)) {
            if (0 === strpos($date, '-')) {
                return substr($date, 1).$suffixes['BBY'];
            }

            return $date.$suffixes['ABY'];
        }

        // For dates already having BBY/ABY
        if (preg_match('# [AB]BY#', $date)) {
            // Replace any minus before a number
            $date = (string) preg_replace('#-([0-9.]+)#', '$1', $date);

            // Replace the suffixes
            return (string) preg_replace_callback(
                '# ([AB]BY)#',
                static function ($matches) use ($suffixes) {
                    return $suffixes[$matches[1]];
                },
                $date
            );
        }

        return $date;
    }

    protected function getStarWarsDateSuffixes(): array
    {
        return [
            'BBY' => '&nbsp;<abbr title="Before the Battle of Yavin IV">BBY</abbr>',
            'ABY' => '&nbsp;<abbr title="After the Battle of Yavin IV">ABY</abbr>',
        ];
    }
}
