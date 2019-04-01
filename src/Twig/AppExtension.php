<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 21.03.2019
 * Time: 22:31
 */

namespace App\Twig;


use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigTest;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }


    public function getGlobals()
    {
        return [
            'locale' => $this->locale
        ];
    }


    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter'])
        ];
    }


    public function priceFilter($number){
        return '$' . number_format($number, 2, '.', ',');
    }

    public function getTests()
    {
        return [
            new TwigTest(
                'like',  function($obj) {
                return $obj instanceof LikeNotification;
            })
        ];
    }
}