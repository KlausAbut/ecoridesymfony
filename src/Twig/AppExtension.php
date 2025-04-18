<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('base64', [$this, 'base64Encode']),
            new TwigFilter('repeat', [$this, 'repeatFilter']),
        ];
    }

    public function base64Encode($value): string
    {
        return base64_encode(stream_get_contents($value));
    }

    public function repeatFilter(string $str, int $times): string
    {
        return str_repeat($str, $times);
    }
}