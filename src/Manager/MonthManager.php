<?php

namespace App\Manager;

use IntlDateFormatter;
use Symfony\Component\HttpFoundation\RequestStack;

class MonthManager
{
    private $requestStack;
    private $months;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        $this->initArray();
    }

    public function initArray()
    {
        $arrayMonths = [];
        $date = new \DateTime('2020-01-01');

        $formatter = new IntlDateFormatter(
            $this->requestStack->getCurrentRequest()->getLocale(),
            IntlDateFormatter::SHORT,
            IntlDateFormatter::SHORT
        );

        $formatter->setPattern('MMMM');

        for ($i = 1; $i < 13; $i++) {
            $month = ucfirst($formatter->format($date));
            $arrayMonths[$i] = $month;

            $date->modify('+1 month');
        }

        $this->months = $arrayMonths;
    }

    public function getList(bool $inversed, bool $short = false): array
    {
        $months = $this->months;

        if ($short)
        {
            foreach ($months as $key => $month)
            {
                $months[$key] = substr($month, 0, 4);
            }
        }

        return $inversed ? array_flip($months) : $months;
    }
}
