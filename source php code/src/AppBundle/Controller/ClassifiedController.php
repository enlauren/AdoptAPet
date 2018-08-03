<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Classified;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ClassifiedController extends Controller
{
    /**
     * @Route("/adopta/{id}", name="adoptat")
     * @Method({"POST"})
     *
     * @param Request    $request
     * @param Classified $classified
     *
     * @return Response
     */
    public function adoptatAction(Request $request, Classified $classified)
    {
        $classified->setFlag($classified->getFlag() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($classified);
        $em->flush();

        return $this->redirectToRoute('classified.single', [
            'slug' => $classified->getSlug()
        ]);
    }

    /**
     * @Route("/adoptat/{id}", name="adoptat.get")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Classified $classified
     *
     * @return Response
     */
    public function getAdoptatAction(Request $request)
    {
        return new Response('', Response::HTTP_FORBIDDEN);
    }
}
