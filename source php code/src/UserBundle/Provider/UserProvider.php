<?php
declare(strict_types = 1);

namespace App\UserBundle\Provider;

use App\UserBundle\Entity\Factory\UserFactory;
use App\UserBundle\Entity\User;
use App\UserBundle\Entity\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProvider
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        UserFactory $userFactory,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function get(User $user)
    {
        $userExisting = $this->userRepository->findOneByEmail(
            $user->getEmail()
        );

        if ($userExisting) {
            return $userExisting;
        }

        $user = $this->userFactory->fillPassword($user);

        $this->userRepository->save($user);

        return $user;
    }

    public function getFromEmailAndPhone(string $email, string $phone)
    {
        $user = $this->userRepository->findOneByEmail($email);

        if (!$user) {
            $user = $this->userFactory->createUserFromEmailAndPhone(
                $email,
                $phone
            );

            $this->userRepository->save($user);
        }

        return $user;
    }

    public function getFromRegister(User $user)
    {
        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword(
                    $user,
                    $user->getPlainPassword()
                )
        );

        $this->userRepository->save($user);

        return $user;
    }
}
