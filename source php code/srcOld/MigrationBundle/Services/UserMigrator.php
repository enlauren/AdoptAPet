<?php

namespace MigrationBundle\Services;

use DateTime;
use MigrationBundle\Repository\ClassifiedRepository;
use MigrationBundle\Repository\UserRepository;
use MigrationBundle\Utils\PhoneCleaner;
use UserBundle\Entity\User;
use UserBundle\Entity\Repository\UserRepository as DoctrineUserRepository;
use UserBundle\Factory\PasswordFactory;

class UserMigrator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var DoctrineUserRepository
     */
    private $doctrineUserRepository;

    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;

    /**
     * @var PasswordFactory
     */
    private $passwordFactory;

    /**
     * @var PhoneCleaner
     */
    private $phoneCleaner;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        DoctrineUserRepository $doctrineUserRepository,
        ClassifiedRepository $classifiedRepository,
        PasswordFactory $passwordFactory,
        PhoneCleaner $phoneCleaner
    )
    {
        $this->userRepository         = $userRepository;
        $this->doctrineUserRepository = $doctrineUserRepository;
        $this->classifiedRepository   = $classifiedRepository;
        $this->passwordFactory = $passwordFactory;
        $this->phoneCleaner = $phoneCleaner;
    }

    public function createUsersFromClassifieds()
    {
        print "Create users from old users: " . PHP_EOL;

        foreach ($this->userRepository->findAll() as $oldUser) {
            $user = new User();
            $user->setActive((bool)$oldUser['active']);
            $user->setUsername($oldUser['username']);
            $user->setEmail($oldUser['email']);
            $user->setPassword($oldUser['password']);
            $user->setCreatedAt(new DateTime($oldUser['created_at']));
            $user->setUpdatedAt(new DateTime($oldUser['updated_at']));
            $user->setCode($oldUser['code']);

            $this->doctrineUserRepository->save($user);
        }

        print "Create users from classifieds: " . PHP_EOL;
        foreach ($this->classifiedRepository->findAll() as $classified) {

            if ($classified['email'] == 'rita_olaru@yahoo.cpm') {
                $classified['email'] = 'rita_olaru@yahoo.com';
            }

            if ($classified['email'] == 'tans_el93@yahoo.com') {
                $classified['phone'] = '';
            }

            /** @var User $user */
            $user = $this->doctrineUserRepository->findOneBy([
                'email' => $classified['email']
            ]);

            if ($user) {
                # User exists
                if (!$user->getPhone()) {
                    $user->setPhone($this->phoneCleaner->clean($classified['phone']));
                }

                $this->doctrineUserRepository->save($user);
            } else {
                $user = new User();
                $user->setPhone($this->phoneCleaner->clean($classified['phone']));
                $user->setActive(true);
                $user->setPassword($this->passwordFactory->generate());
                $user->setEmail($classified['email']);
                $user->setUsername(
                    explode('@', $classified['email'])[0]
                );

                $this->doctrineUserRepository->save($user);
            }

        }
    }
}
