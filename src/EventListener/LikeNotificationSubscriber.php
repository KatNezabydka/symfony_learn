<?php

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LikeNotificationSubscriber implements EventSubscriber {

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        /** @var PersistentCollection $collectionUpdate */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            // является ли событие обьектом поста
            if (!$collectionUpdate->getOwner() instanceof MicroPost) {
                continue;
            };
            //показать информацию о событии когда проихошло
            //dump($collectionUpdate->getMapping());

            // нам нужно только событие лайк на посте
            if ('likedBy' !== $collectionUpdate->getMapping()['fieldName']) {
                continue;
            };
            // массив элементов которые добавили в колекцию (наш лайк)
            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff)) {
                return;
            }
            /** @var MicroPost $microPost */
            $microPost = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            // берем первый элемент с array (т.к 2 обьекта прилететь не может)
            $notification->setLikedBy(reset($insertDiff));

            $em->persist($notification);

            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );


        }
    }
}
