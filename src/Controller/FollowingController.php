<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 29.03.2019
 * Time: 20:38
 */

namespace App\Controller;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/following")
 * @Security("is_granted('ROLE_USER')")
 */
class FollowingController extends AbstractController {

    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        // Только если мы не пытаемся подписаться на самого себя
        if ($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->follow($userToFollow);

            $this->getDoctrine()->getManager()->flush();

        }
        return $this->redirectToRoute('micro_post_user', ['username' => $userToFollow->getUsername()]);

    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        // many to many store
        $currentUser->getFollowing()->removeElement($userToUnFollow);

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('micro_post_user', ['username' => $userToUnFollow->getUsername()]);
    }
}