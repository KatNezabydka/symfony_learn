<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 30.03.2019
 * Time: 17:15
 */

namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/likes")
 */
class LikesController extends AbstractController {

    /**
     * @Route("/likes/{id}", name="likes_like")
     */
    public function like(MicroPost $microPost)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        // Если пользователь не авторизованый, вернем ответ для js
        if (!$currentUser instanceof User) {
            return new JsonResponse([],  Response::HTTP_UNAUTHORIZED);
        }
        $microPost->like($currentUser);

        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
           'count' => $microPost->getLikedBy()->count()
        ]);

    }

    /**
     * @Route("/unlikes/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $microPost)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        // Если пользователь не авторизованый, вернем ответ для js
        if (!$currentUser instanceof User) {
            return new JsonResponse([],  Response::HTTP_UNAUTHORIZED);
        }
        $microPost->getLikedBy()->removeElement($currentUser);

        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);

    }

}