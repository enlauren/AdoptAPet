<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Application\Command\CreatePetMedicalCenterCommand;
use App\AppBundle\Application\Handler\CreatePetMedicalCenterHandler;
use App\AppBundle\Entity\Cabinet;
use App\AppBundle\Entity\City;
use App\AppBundle\Entity\Repository\CabineteRepository;
use App\AppBundle\Form\PetMedicalCenterType;
use App\AppBundle\Services\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PetMedicalCenterController extends Controller
{
    /**
     * @var CabineteRepository
     */
    private $cabineteRepository;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var CreatePetMedicalCenterHandler
     */
    private $createPetMedicalCenterHandler;

    public function __construct(
        CabineteRepository $cabineteRepository,
        Paginator $paginator,
        CreatePetMedicalCenterHandler $createPetMedicalCenterHandler
    ) {
        $this->cabineteRepository = $cabineteRepository;
        $this->paginator = $paginator;
        $this->createPetMedicalCenterHandler = $createPetMedicalCenterHandler;
    }

    /**
     * @Route("/cabinete-veterinare/{slug}", name="vet.list", requirements={
     *     "slug": "^(?!adauga$)[^ \/]+$"
     * })
     * @Template()
     *
     * @param City $city
     *
     * @return array
     */
    public function listAllAction(City $city)
    {
        $vets = $this->cabineteRepository->findByCity($city);

        return [
            'vets' => $this->paginator->paginate($vets),
            'city' => $city,
            'title' => $this->get('translator')->trans('title_listing_pet_medical_center', ['%city%' => $city->getName()]),
        ];
    }

    /**
     * @Route("/cabinete-veterinare/non-stop/{slug}", name="vet.list.nonstop", requirements={
     *     "slug": "^(?!adauga$)[^ \/]+$"
     * })
     * @Template()
     *
     * @param City $city
     *
     * @return array
     */
    public function listAllNonStopAction(City $city)
    {
        // todo update here to find only non stop medical centers
        $vets = $this->cabineteRepository->findByCity($city);

        return [
            'vets' => $this->paginator->paginate($vets),
            'city' => $city,
            'title' => $this->get('translator')->trans('title_listing_pet_medical_center_non_stop', ['%city%' => $city->getName()]),
        ];
    }

    /**
     * @Route("/cabinete-veterinare/adauga", name="vet.add")
     * @Template()
     *
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $petMedicalCenter = $this->cabineteRepository->create();

        $form = $this->createForm(PetMedicalCenterType::class, $petMedicalCenter);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $command = new CreatePetMedicalCenterCommand($petMedicalCenter);
            $this->createPetMedicalCenterHandler->handle($command);

            return $this->redirectToRoute('vet.single', ['slug' => $petMedicalCenter->getSlug()]);
        }

        return [
            'form'  => $form->createView(),
            'title' => $this->get('translator')->trans('title_create_pet_medical_center')
        ];
    }

    /**
     * @Route("/cv/{slug}", name="vet.single")
     * @Template()
     *
     * @param Cabinet $medicalCenter
     *
     * @return array
     */
    public function singleAction(Cabinet $medicalCenter)
    {
        return [
            'this' => $medicalCenter
        ];
    }
}
