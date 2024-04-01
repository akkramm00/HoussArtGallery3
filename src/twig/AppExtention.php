<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('min_to_hour', [$this, 'minutes¨ToHours']),
            new TwigFilter('int_to_decimal', [$this, 'integerToDecimal']),
        ];
    }

    public function minutesToHours($value)
    {
        if ($value < 60 || !$value) {
            return $value;
        }

        $hours = floor($value / 60);
        $minutes = $value % 60;

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        $time = sprintf('%sh%s', $hours, $minutes);

        return $time;
    }

    public function integerToDecimal($value)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        return number_format((float)$value, 2, '.', '');
    }
}
