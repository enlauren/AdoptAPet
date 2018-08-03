<?php
declare(strict_types = 1);

namespace App\UserBundle\Entity\Factory;

use App\UserBundle\Entity\User;
use App\UserBundle\Factory\PasswordFactory;

class UserFactory
{
    /**
     * @var PasswordFactory
     */
    protected $passwordFactory;

    public function __construct(PasswordFactory $passwordFactory)
    {
        $this->passwordFactory = $passwordFactory;
    }

    public function create()
    {
        return new User;
    }

    /**
     * @param string $email
     * @param string $phone
     *
     * @return User
     */
    public function createUserFromEmailAndPhone(string $email, string $phone) : User
    {
        $user = $this->create();
        $user->setEmail($email);
        $user->setPhone($phone);

        $user = $this->fillPassword($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function fillPassword(User $user): User
    {
        if ($user->getPassword()) {
            return $user;
        }

        $user->setPassword($this->passwordFactory->generate());

        return $user;
    }
}
