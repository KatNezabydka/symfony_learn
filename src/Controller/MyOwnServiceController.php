<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 22.03.2019
 * Time: 19:52
 */

namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyOwnServiceController extends AbstractController
{
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    /**
     * @Route("/greet", name="greet")
     */
    public function index(Request $request){
        return new Response($this->greeting->greet($request->get('name')));
    }

}