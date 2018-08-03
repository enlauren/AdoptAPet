<?php
declare(strict_types = 1);

namespace App\UserBundle\Controller;

use App\AppBundle\Entity\Repository\ClassifiedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
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
     * @Route("/user/dashboard", name="dashboard")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $classifieds = $this->classifiedRepository->findByUserAndPaginate(
            $this->getUser(),
            $request->request->get('page', 1)
        );

        dump($classifieds);
        return $this->render('User/Listing/index.html.twig', [
            'user' => $this->getUser(),
            'classifieds' => $classifieds
        ]);
    }
}
