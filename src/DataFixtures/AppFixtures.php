<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => 'katnezabydka',
            'email' => 'katorifmmm@gmail.com',
            'password' => '111111',
            'fullName' => 'Kat Nezabydka',
            'roles' => [User::ROLE_ADMIN]
        ],
        [
            'username' => 'test_one',
            'email' => 'test_one@gmail.com',
            'password' => '111111',
            'fullName' => 'Test One',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'test_two',
            'email' => 'test_two@gmail.com',
            'password' => '111111',
            'fullName' => 'Test Two',
            'roles' => [User::ROLE_USER]
            ],
        [
            'username' => 'test_three',
            'email' => 'test_three@gmail.com',
            'password' => '111111',
            'fullName' => 'Test Three',
            'roles' => [User::ROLE_USER]
            ]
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor', 'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    private const LANGUAGES = [
      'en',
      'fr'
    ];

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

        for ($i = 0; $i < 50; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]);
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $microPost->setTime(new \DateTime());
            $microPost->setUser($this->getReference(self::USERS[rand(0, count(self::USERS) - 1)]['username']));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {

        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullname($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);
            $this->addReference($userData['username'], $user);

            $preferences = new UserPreferences();
            $preferences->setLocale(self::LANGUAGES[rand(0, 1)]);

            $user->setPreferences($preferences);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
