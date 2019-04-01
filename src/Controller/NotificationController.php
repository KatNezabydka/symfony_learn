<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 31.03.2019
 * Time: 11:42
 */

namespace App\Controller;


use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notification")
 * @Security("is_granted('ROLE_USER')")
 */
class NotificationController extends AbstractController {

    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unReadCount()
    {
        return new JsonResponse(
            [
                'count' => $this->notificationRepository->findUnseenByUser($this->getUser())
            ]
        );
    }


    /**
     * @Route("/acknowledge-all", name="notifications_acknowledge_all")
     */
    public function acknowledgeAll()
    {
        $this->notificationRepository->markAllAsReadByUser($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('notifications_all');
    }


    /**
     * @Route("/acknowledge{id}", name="notifications_acknowledge")
     */
    public function acknowledge(Notification $notification)
    {
        $notification->setSeen(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('notifications_all');
    }

    /**
     * @Route("/all", name="notifications_all")
     */
    public function notifications()
    {
        return $this->render('notification/notifications.html.twig', [
            'notifications' => $this->notificationRepository->findBy([
                'seen' => false,
                'user' => $this->getUser()
            ])
        ]);
    }




}