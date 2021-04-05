<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('age', [$this, 'getAge']),
        ];
    }

    public function getAge($date) 
    {
       if (!$date instanceof \DateTime) {

        return null;
        }
        
    $referenceDate = date('01-01-Y');
    $referenceDateTimeObject = new \DateTime($referenceDate);
    $diff = $referenceDateTimeObject->diff($date);
    return $diff->y;
   }
}