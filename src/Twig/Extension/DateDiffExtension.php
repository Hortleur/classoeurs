<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\DateDiffExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateDiffExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('diff', [$this, 'dateDiff']),
        ];
    }

    public function dateDiff(\DateTimeImmutable $date, \DateTimeImmutable $now = null)
    {
        $now = new \DateTimeImmutable();
        $diff = $date->diff($now);

        return $diff->format('%h heures');
    }
}
