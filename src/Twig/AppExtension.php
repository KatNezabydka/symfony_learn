<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 21.03.2019
 * Time: 22:31
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter'])
        ];
    }

    public function priceFilter($number){
        return '$' . number_format($number, 2, '.', ',');
    }
}