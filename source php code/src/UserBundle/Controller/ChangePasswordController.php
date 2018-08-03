<?php
declare(strict_types = 1);

namespace App\UserBundle\Controller;

use App\UserBundle\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ChangePasswordController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/user/change_password", name="dashboard.change_password")
     * @Template()
     *
     * @return []
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('user_bundle.repository.user')->save($user);

            return $this->redirectToRoute('dashboard');
        }

        return [
            'form' => $form->createView()
        ];
    }
}
