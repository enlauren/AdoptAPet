<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Repository\ClassifiedRepository;
use App\AppBundle\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TypeController extends Controller
{
    /**
     * @Route("/adoptii/{slug}", name="adoptii.type")
     * @ParamConverter("type", class="App\AppBundle:Type")
     *
     * @param Request $request
     * @param Type $type
     *
     * @param ClassifiedRepository $classifiedRepository
     * @return Response
     */
    public function typeAction(Request $request, Type $type, ClassifiedRepository $classifiedRepository)
    {
        $viewParams = [
            'classifieds' => $classifiedRepository->getByType($type, $request->query->getInt('page', 1)),
            'type' => $type,
            'title' => $this->get('translator')->trans('title_type_listing_' . $type->getSlug()),
        ];

        return $this->render('listing/type.html.twig', $viewParams);
    }
}
