<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Repository\ClassifiedRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     * @param ClassifiedRepository $classifiedRepository
     * @return Response
     */
    public function index(
        Request $request,
        ClassifiedRepository $classifiedRepository
    ) {
        $viewParams = [
            'classifieds' => $classifiedRepository->page(
                $request->query->getInt('page', 1)
            ),
            'title' => $this->get('translator')->trans('title_homepage')
        ];

        // replace this example code with whatever you need
        return $this->render('listing/index.html.twig', $viewParams);
    }
}
