<?php
declare(strict_types=1);

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="admin.dashboard")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function dashboardAction(Request $request)
    {


        return [];
    }
}