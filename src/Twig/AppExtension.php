<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('convert_base64', [$this, 'convertBase64']),
            new TwigFilter('repeat', [$this, 'repeatFilter']),
        ];
    }

    public function convertBase64($value): string
    {
        if (is_resource($value)) {
            return base64_encode(stream_get_contents($value));
        }
        return base64_encode($value);
    }

    public function repeatFilter(string $str, int $times): string
    {
        return str_repeat($str, $times);
    }
}
