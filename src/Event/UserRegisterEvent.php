<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 01.04.2019
 * Time: 22:51
 */

namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserRegisterEvent extends Event {

    const NAME = 'user.register';

    /**
     * @var User
     */
    private $registerUser;

    public function __construct(User $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    /**
     * @return User
     */
    public function getRegisterUser(): User
    {
        return $this->registerUser;
    }


}