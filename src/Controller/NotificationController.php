<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 31.03.2019
 * Time: 11:42
 */

namespace App\Controller;


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
}