<?php
declare(strict_types = 1);

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use App\UserBundle\Exception\EmailAlreadyUsedException;
use App\UserBundle\Form\RegistrationType;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        $csrfToken = $this->get('security.csrf.token_manager')
            ->getToken('registration')
            ->getValue();

        if ($form->isValid()) {
            $user = $form->getData();
            $user = $this->get('user_bundle.provider.user')
                ->getFromRegister($user);

            $this->loginUser($request, $user);

            return $this->redirectToRoute('dashboard');
        }

        return $this->render(
            ':security/Registration:register.html.twig', [
                'csrf_token' => $csrfToken,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @param User $user
     */
    protected function loginUser(Request $request, User $user)
    {
        $token = new UsernamePasswordToken(
            $user,
            null,
            "main",
            $user->getRoles()
        );

        $this
            ->get("security.token_storage")
            ->setToken($token);

        $event = new InteractiveLoginEvent(
            $request,
            $token
        );
        $this
            ->get("event_dispatcher")
            ->dispatch(
                SecurityEvents::INTERACTIVE_LOGIN,
                $event
            );
    }
}
