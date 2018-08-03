<?php

namespace MigrationBundle\Services;

use AppBundle\Entity\Classified;
use AppBundle\Entity\Repository\CityRepository;
use AppBundle\Entity\Repository\TypeRepository;
use DateTime;
use ImageBundle\Entity\Image;
use MigrationBundle\Repository\CitiesToClassifiedsRepository;
use MigrationBundle\Repository\ClassifiedRepository;
use MigrationBundle\Repository\ImageRepository;
use MigrationBundle\Repository\UserRepository;
use UserBundle\Entity\User;
use UserBundle\Entity\Repository\UserRepository as DoctrineUserRepository;
use AppBundle\Entity\Repository\ClassifiedRepository as DoctrineClassifiedRepository;

class ClassifiedMigrator
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
     * @var DoctrineClassifiedRepository
     */
    private $doctrineClassifiedRepository;

    private static $type = [
        'dog' => 2,
        'cat' => 1
    ];

    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * @var CitiesToClassifiedsRepository
     */
    private $citiesToClassifiedsRepository;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * @param UserRepository                $userRepository
     * @param DoctrineUserRepository        $doctrineUserRepository
     * @param ClassifiedRepository          $classifiedRepository
     * @param DoctrineClassifiedRepository  $doctrineClassifiedRepository
     * @param TypeRepository                $typeRepository
     * @param CitiesToClassifiedsRepository $citiesToClassifiedsRepository
     * @param CityRepository                $cityRepository
     */
    public function __construct(
        UserRepository $userRepository,
        DoctrineUserRepository $doctrineUserRepository,
        ClassifiedRepository $classifiedRepository,
        DoctrineClassifiedRepository $doctrineClassifiedRepository,
        TypeRepository $typeRepository,
        CitiesToClassifiedsRepository $citiesToClassifiedsRepository,
        CityRepository $cityRepository,
        ImageRepository $imageRepository
    )
    {
        $this->userRepository               = $userRepository;
        $this->doctrineUserRepository       = $doctrineUserRepository;
        $this->classifiedRepository         = $classifiedRepository;
        $this->doctrineClassifiedRepository = $doctrineClassifiedRepository;
        $this->typeRepository               = $typeRepository;
        $this->citiesToClassifiedsRepository = $citiesToClassifiedsRepository;
        $this->cityRepository = $cityRepository;
        $this->imageRepository = $imageRepository;
    }

    public function migrateClassifieds()
    {
        print "Starting migrating classifieds." . PHP_EOL;

        foreach ($this->classifiedRepository->findAll() as $oldClassified) {
            /** @var User $user */
            $user = $this->doctrineUserRepository->findOneBy([
                'email' => $oldClassified['email']
            ]);

            $decodedDescription = utf8_decode($oldClassified['description']);
            if ($decodedDescription != $oldClassified['description']) {
                print "decode description for " . $oldClassified['email'] . PHP_EOL;
                $oldClassified['description'] = $decodedDescription;
            }

            $decodedTitle = utf8_decode($oldClassified['title']);

            if ($decodedTitle != $oldClassified['title']) {
                print "decode title for " . $oldClassified['email'] . PHP_EOL;
                $oldClassified['title'] = $decodedTitle;
            }

            $classified = new Classified();

            if ($user) {
                $classified->setUser($user);
            }

            $classified->setId($oldClassified['id']);
            $classified->setTitle($oldClassified['title']);
            $classified->setDescription($oldClassified['description']);
            $classified->setCreatedAt(new DateTime($oldClassified['created_at']));
            $classified->setUpdatedAt(new DateTime($oldClassified['updated_at']));
            $classified->setRefreshedAt(new DateTime($oldClassified['refresh']));

            if ($oldClassified['reminded_at'] != '0000-00-00 00:00:00') {
                $classified->setRemindedAt(new DateTime($oldClassified['reminded_at']));
            }

            $classified->setReminders($oldClassified['reminders']);
            $classified->setEmail($oldClassified['email']);
            $classified->setPhone($oldClassified['phone']);
            $classified->setExpired($oldClassified['adopted'] == 'yes');
            $classified->setViews($oldClassified['views']);
            $classified->setSlug($oldClassified['slug']);

            $type = $this->typeRepository->find(self::$type[$oldClassified['type']]);

            $classified->setType($type);
            $classified->setGender($oldClassified['gen']);
            $classified->setFlag($oldClassified['flag']);
            $classified->setToken($oldClassified['token']);
            $classified->setPriority($oldClassified['priority']);

            if ($oldClassified['deleted_at']) {
                $classified->setDeletedAt(new DateTime($oldClassified['deleted_at']));
            }

            $classified->setIp($oldClassified['ip']);
            $classified->setAllowedComments((bool)$oldClassified['comments']);
            // todo images

            $images = [];
            $firstImage = true;

            foreach($this->imageRepository->findByClassifiedId($oldClassified['id']) as $oldImage) {
                $image = new Image();
                $image->setClassified($classified);

                $imageParts = explode('/', $oldImage['image']);
                end($imageParts);

                $image->setFile(current($imageParts));

                if (true === $firstImage) {
                    $thumbsParts = explode('/', $oldClassified['thumb']);
                    end($thumbsParts);

                    $image->setThumb(current($thumbsParts));
                }

                $images[] = $image;
                $firstImage = false;
            }

            $classified->setImages($images);
            $classified->setOldId($oldClassified['id']);

            $this->doctrineClassifiedRepository->saveBatch($classified);
        }

        $this->doctrineClassifiedRepository->flush();
    }

    public function associateCitiesToClassifieds()
    {
        /** @var Classified $classified */
        foreach ($this->classifiedRepository->findAll() as $classified) {
            $classifiedObject = $this->doctrineClassifiedRepository->findOneBy([
                'slug' => $classified['slug']
            ]);

            print "Classified: " . $classified['title'] . PHP_EOL;
            print PHP_EOL;

            foreach ($this->citiesToClassifiedsRepository->findByClassifiedId($classified['id']) as $city) {
                $city = $this->cityRepository->find($city['jud_id']);

                print " -- adding city: " . $city->getName() . PHP_EOL;

                $city->addClassified($classifiedObject);
                $classifiedObject->addCity($city);
            }

            print "Saving: " . $classifiedObject->getTitle() . PHP_EOL;

            $this->doctrineClassifiedRepository->saveBatch($classifiedObject);
        }

        $this->doctrineClassifiedRepository->flush();
    }
}
