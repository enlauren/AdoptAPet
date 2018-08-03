<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\AppBundle\Entity\City;
use App\AppBundle\Entity\Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PagesController extends Controller
{
    /**
     * @Route("/despre-noi", name="about_us")
     *
     * @return Response
     */
    public function getAboutUsAction()
    {
        return $this->render('App\AppBundle:Page:about_us.html.twig');
    }

    /**
     * @Route("/contact", name="contact_us")
     *
     * @return Response
     */
    public function getContactAction()
    {
        return $this->render('App\AppBundle:Page:contact_us.html.twig');
    }
}
