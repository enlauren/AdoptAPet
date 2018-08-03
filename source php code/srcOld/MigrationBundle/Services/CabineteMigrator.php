<?php
declare(strict_types = 1);

namespace MigrationBundle\Services;

use AppBundle\Entity\Cabinet;
use AppBundle\Entity\Repository\CityRepository;
use MigrationBundle\Repository\CabineteRepository;

class CabineteMigrator
{
    /**
     * @var CabineteRepository
     */
    private $cabineteRepository;
    /**
     * @var CityRepository
     */
    private $cityRepository;
    /**
     * @var \AppBundle\Entity\Repository\CabineteRepository
     */
    private $doctrineCabineteRepository;

    public function __construct(CabineteRepository $cabineteRepository, CityRepository $cityRepository, \AppBundle\Entity\Repository\CabineteRepository $doctrineCabineteRepository)
    {
        $this->cabineteRepository         = $cabineteRepository;
        $this->cityRepository             = $cityRepository;
        $this->doctrineCabineteRepository = $doctrineCabineteRepository;
    }

    public function migrate()
    {
        foreach ($this->cabineteRepository->findAll() as $cabinetRaw) {
            $cabinet = new Cabinet();
            $cabinet->setEmail($cabinetRaw['email']);
            $cabinet->setTitle($cabinetRaw['nume']);
            $cabinet->setDescription($cabinetRaw['descriere']);
            $cabinet->setPhone($cabinetRaw['telefon']);
            $cabinet->setAddress($cabinetRaw['adresa']);
            $cabinet->setSchedule($cabinetRaw['program']);
            $cabinet->setWebsite($cabinetRaw['website']);
            $cabinet->setNonstop($cabinetRaw['nonstop'] == 1);
            $cabinet->setFlag((int)$cabinetRaw['flag']);
            $cabinet->setUpdatedAt(new \DateTime($cabinetRaw['updated_at']));
            $cabinet->setCreatedAt(new \DateTime($cabinetRaw['created_at']));
            $cabinet->setCity(
                $this->cityRepository->find($cabinetRaw['jud_id'])
            );

            $this->doctrineCabineteRepository->save($cabinet);
        };
    }
}
