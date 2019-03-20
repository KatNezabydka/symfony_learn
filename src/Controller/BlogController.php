<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 16.03.2019
 * Time: 18:11
 */

namespace App\Controller;


use App\Service\Greeting;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;


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
     * @Route("/", name="index")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
//        return new Response('IT IS WORK!!!');
    }


    /**
     * @Route("/news/{slug}", name="news.show")
     */
    public function show($slug)
    {
        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];
        return $this->render('article/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
            'slug' => $slug
        ]);
    }

    /**
     * @Route("news/{slug}/heart", name="new_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {
        $logger->info("Article is being hearted");
        return new JsonResponse(['hearts' => rand(5, 100)]);
    }



}