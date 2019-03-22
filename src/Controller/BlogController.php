<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 21.03.2019
 * Time: 21:12
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{


    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {

        $this->session = $session;
    }

    /**
     * @route("/", name="blog_index")
     */
    public function index()
    {
//        $this->session->clear();
        return $this->render('blog/index.html.twig',
            [
                'posts' => $this->session->get('posts')
            ]);

    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts =  $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title' . rand(1, 500),
            'text' => 'Some random text' . rand(1, 500),
            'date' => new \DateTime(),
        ];
        $this->session->set('posts', $posts);

        return $this->redirectToRoute('blog_index');

    }

    /**
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id)
    {
        $posts =  $this->session->get('posts');
        if (!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('Post not found');
        }

        return $this->render('blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id],
            ]);
    }

}