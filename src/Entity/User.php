<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Этот email уже используется")
 * @UniqueEntity(fields="username", message="Это имя уже используется")
 */
class User implements UserInterface, \Serializable {
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @Assert\Length(min="5", max="50", maxMessage="Поле не может превышать {max} символов",  minMessage="Поле не может быть меньше {min} символов")
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @Assert\Length(min="6", max="4096", maxMessage="Поле не может превышать {max} символов",  minMessage="Поле не может быть меньше {min} символов")
     */
    //это поле никогда не будет сохранено в бд
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @Assert\Email(message="Поле должно соответствовать типо email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @Assert\Length(min="4", max="50", maxMessage="Поле не может превышать {max} символов",  minMessage="Поле не может быть меньше {min} символов")
     */
    private $fullName;

    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User",  inversedBy="followers")
     * @ORM\JoinTable(name="following",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="following_user_id", referencedColumnName="id")
     *     }
     *     )
     */
    private $following;

    // Doctrine никогда не использует конструктор, он нужен для формировния коллекций в связях (например один ко многим)
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }


    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password)
            = unserialize($serialized);
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return mixed
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @return mixed
     */
    public function getFollowing()
    {
        return $this->following;
    }


}
