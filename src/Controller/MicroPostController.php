<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 23.03.2019
 * Time: 16:32
 */

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/micro-post")
 */
//Здесь все зависсимости писали вручную без использования  extends AbstractController
class MicroPostController extends AbstractController {
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(MicroPostRepository $microPostRepository, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    /**
     * @route("/", name="micro_post_index")
     */
    public function index(UserRepository $userRepository)
    {
        //1) Показываем посты тех пользователей на который подписаны
        //2) Если не подписаны не на кого - показываем пользователей, на которых можно подписаться

        $currentUser = $this->getUser();

        $usersToFollow = [];
        // авторизирован ли пользователь или нет
        if ($currentUser instanceof User) {
            $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());

            $usersToFollow = count($posts) === 0 ? $userRepository->findAllWithMoreThan5PostsExceptUser($currentUser) : [];
        }
        else {
            $posts = $this->microPostRepository->findBy(
                [],
                ['time' => 'DESC']
            );
        }
        return $this->render('micro-post/index.html.twig', [
            'posts' => $posts,
            'usersToFollow' => $usersToFollow
        ]);

    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @Security("is_granted('edit', microPost)", message="Доступ запрещен")
     */
    public function edit(MicroPost $microPost, Request $request)
    {
        // Разрешить редактировтаь только если есть доступ
        $this->denyAccessUnlessGranted('edit', $microPost);

        $form = $this->formFactory->create(MicroPostType::class, $microPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('micro-post/add.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')", message="Для добавления записи нужна авторизация")
     */
    public function add(Request $request)
    {
        //@Security обеспечил доступ к этому методу только авторизированому пользователю
        $user = $this->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($user);
        // если хотим время добавлять автоматически
        // $microPost->setTime(new \DateTime());

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        // валидация данных???
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('micro-post/add.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete', microPost)", message="Доступ запрещен")
     */
    public function delete(MicroPost $microPost)
    {
        // Разрешить удалять только если есть доступ
        //        $this->denyAccessUnlessGranted('delete', $microPost);

        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        // Сохраняем флеш сообщение в сессии
        $this->flashBag->add('notice', 'Пост был удален');
        return $this->redirectToRoute('micro_post_index');

    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPost(User $userWithPosts)
    {
        // находим посты, которые по 1му параметру (критерия) являются постами пользователей
        return $this->render('micro-post/user-post.html.twig', [
            'posts' => $this->microPostRepository->findBy(
                ['user' => $userWithPosts],
                ['time' => 'DESC']
            ),
            'user'  => $userWithPosts
            // lazy load
            //'posts' => $userWithPosts->getPosts()
        ]);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post)
    {
        return $this->render('micro-post/post.html.twig', ['post' => $post]);

    }

}