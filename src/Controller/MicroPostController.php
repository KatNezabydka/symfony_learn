<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 23.03.2019
 * Time: 16:32
 */

namespace App\Controller;
use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    public function __construct(MicroPostRepository $microPostRepository)
    {

        $this->microPostRepository = $microPostRepository;
    }

    /**
     * @route("/", name="micro-post-index")
     */
    public function index()
    {
        return $this->render('micro-post/index.html.twig', [
            'posts' =>$this->microPostRepository->findAll()
        ]);
    }
}