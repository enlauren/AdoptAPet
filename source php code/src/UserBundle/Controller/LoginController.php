<?php
declare(strict_types = 1);

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $csrfToken    = $this
            ->get('security.csrf.token_manager')
            ->getToken('authenticate')
            ->getValue();

        return $this->render(
            '@UserBundle/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token'    => $csrfToken,
            ]
        );
    }

    /**
     * @Route("/password/forgot-password", name="forgot_password")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswordAction(Request $request)
    {
        // todo do the actual forgot password
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $csrfToken    = $this
            ->get('security.csrf.token_manager')
            ->getToken('email')
            ->getValue();

        return $this->render(
            '@UserBundle/forgot_password.html.twig',
            [
                'email'      => $lastUsername,
                'error'      => $error,
                'csrf_token' => $csrfToken,
            ]
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {

    }
}
