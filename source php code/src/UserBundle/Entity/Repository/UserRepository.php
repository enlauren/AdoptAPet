<?php
declare(strict_types = 1);

namespace App\UserBundle\Entity\Repository;

use App\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry, User::class);
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush($user);

        return $user;
    }

    public function remove(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    /**
     * Finds a user either by email, or username
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findOneByEmail($usernameOrEmail);
        }

        return $this->findOneByUsername(explode('@', $usernameOrEmail)[0]);
    }
}
