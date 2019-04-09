<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 09.04.2019
 * Time: 18:32
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserLocaleSubscriber implements EventSubscriberInterface {

    /**
     * @var SessionInterface
     */
    private $session;

    // Этот подписчик сработает быстрее чем в сессию запишется local - язык
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [
                [
                    'onInteractiveLogin',
                    //приоритет выполнения
                    15
                ]
            ]
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $this->session->set('_locale', $user->getPreferences()->getLocale());
    }
}