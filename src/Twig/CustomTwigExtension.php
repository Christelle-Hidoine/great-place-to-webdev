<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwigExtension extends AbstractExtension
{
    /**
     * Method to get filter by url
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('urlContains', [$this, 'urlContains']),
        ];
    }

    public function urlContains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}