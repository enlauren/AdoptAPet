<?php
declare(strict_types = 1);

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/user/{user}", name="user_listing")
     *
     * @param Request $request
     * @return Response
     */
    public function userPetsAction(Request $request, User $user)
    {
        $viewParams = [
            'classifieds' => $user->getClassifieds(),
            'user' => $user,
        ];

        return $this->render('User/Listing/index.html.twig', $viewParams);
    }
}
