<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 16.03.2019
 * Time: 18:11
 */

namespace App\Controller;


use App\Service\Greeting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends AbstractController
{

    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }


    /**
     * @Route("/blog", name="blog_index")
     */
    public function index(Request $request)
    {
        return $this->render('base.html.twig', ['message' => $this->greeting->greet(
            $request->get('name')
        )]);
    }


    /**
     * @Route("/")
     */
    public function homepage()
    {
//        return $this->render('base.html.twig');
        return new Response('IT IS WORK!!!');
    }


    /**
     * @Route("/news/{slug}")
     */
    public function show($slug)
    {
        return new Response(sprintf('Future page: %s', $slug));
    }



}