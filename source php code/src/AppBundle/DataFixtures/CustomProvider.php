<?php
declare(strict_types = 1);

namespace App\AppBundle\DataFixtures;

use App\AppBundle\Entity\City;
use App\AppBundle\Entity\Repository\CityRepository;

class CustomProvider
{
    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @return City[]
     */
    public function randomCities()
    {
        $cities = $this->cityRepository->findAll();

        $random = array_rand($cities, 3);

        return [
            $cities[$random[0]],
            $cities[$random[1]],
            $cities[$random[2]],
        ];
    }

    /**
     * @return City[]
     */
    public function randomCity()
    {
        $cities = $this->cityRepository->findAll();
        $random = array_rand($cities, 1);

        return $cities[$random[0]];
    }
}
