<?php
/**
 * Created by PhpStorm.
 * User: Kat
 * Date: 25.03.2019
 * Time: 19:57
 */

namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{

    const EDIT = 'edit';
    const DELETE = 'delete';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecision;

    // Внедряем проверку по ролям
    public function __construct(AccessDecisionManagerInterface $accessDecision)
    {
        $this->accessDecision = $accessDecision;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof MicroPost){
            return false;
        }

        return true;

    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // если пользватель админ - даем ему все возможные права
        if ($this->accessDecision->decide($token, [User::ROLE_ADMIN])){
            return true;
        }
        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User){
            return false;
        }

        /** @var MicroPost $microPost*/
        $microPost = $subject;

       return $microPost->getUser()->getId() === $authenticatedUser->getId();

    }
}