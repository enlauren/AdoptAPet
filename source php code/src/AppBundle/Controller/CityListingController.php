<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\City;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use App\AppBundle\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CityListingController extends Controller
{
    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;

    public function __construct(ClassifiedRepository $classifiedRepository)
    {
        $this->classifiedRepository = $classifiedRepository;
    }

    /**
     * @Route("/adoptii/{slug}", name="listing.city", requirements={
     *     "slug": "^(?!caini$|pisici$)[^ \/]+$"
     * })
     *
     * @ParamConverter("city", class="App\AppBundle\Entity\City")
     * @param Request $request
     *
     * @param City $city
     * @return Response
     */
    public function cityAdoptAction(Request $request, City $city)
    {
        $viewParams = [
            'city' => $city,
            'classifieds' => $this->classifiedRepository->findByCityAndPaginate(
                $city,
                $request->query->getInt('page', 1)
            ),
            'title' => $this->get('translator')->trans('title_listing_city', ['%city%' => $city->getName()]),
        ];

        return $this->render('listing/city.html.twig', $viewParams);
    }

    /**
     * @Route("adoptii/{type}/{city}", name="adoptii.caini.city")
     *
     * @ParamConverter("city", class="App\AppBundle\Entity\City", options={"mapping":{"city": "slug"}})
     * @ParamConverter("type", class="App\AppBundle\Entity\Type", options={"mapping":{"type": "slug"}})
     *
     * @param Request $request
     * @param Type    $type
     * @param City    $city
     *
     * @return Response
     */
    public function dogAdoptCityAction(Request $request, Type $type, City $city)
    {
        $viewParams = [
            'city' => $city,
            'type' => $type,
            'title' => $this->get('translator')->trans('title_listing_type_city', [
                '%city%' => $city->getName(),
                '%type%' => $type->getName(),
            ]),
            'classifieds' => $this->classifiedRepository->findByCityAndType(
                $city,
                $type,
                $request->query->getInt('page', 1)
            ),
        ];

        return $this->render('listing/cityType.html.twig', $viewParams);
    }
}
