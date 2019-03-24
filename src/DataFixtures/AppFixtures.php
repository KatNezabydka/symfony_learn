<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicropost($manager);
    }

    public function loadMicropost(ObjectManager $manager)
    {

        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText('Some random text ' . rand(0, 100));
            $microPost->setTime(new \DateTime());
            $microPost->setUser($this->getReference('KatNezabydka'));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('KatNezabydka');
        $user->setFullname('Kat Nezabydka');
        $user->setEmail('katorifmmm@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '111111'));

        $this->addReference('KatNezabydka', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
