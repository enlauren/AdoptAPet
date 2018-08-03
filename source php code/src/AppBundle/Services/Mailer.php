<?php

namespace App\AppBundle\Services;

use App\AppBundle\Entity\Classified;
use App\UserBundle\Entity\User;
use Mailgun\Mailgun;
use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Environment;

class Mailer
{
    /** @var Mailgun */
    private $mailer;

    /** @var string */
    private $domain = "adoptaunsuflet.ro";

    /** @var \Twig_Environment */
    private $twigEnvironment;

    /** @var string */
    private $adminEmail = 'enachetudorel@gmail.com';

    /** @var TranslatorInterface */
    private $translator;

    /** @var string */
    private $from = 'contact@adoptaunsuflet.ro';

    /**
     * @param Twig_Environment    $twigEnvironment
     * @param string              $adminEmail
     * @param Mailgun             $mailer
     * @param TranslatorInterface $translator
     */
    public function __construct(
        Twig_Environment $twigEnvironment,
        $adminEmail,
        $mailer,
        TranslatorInterface $translator
    ) {
        $this->mailer          = $mailer;
        $this->twigEnvironment = $twigEnvironment;
        $this->adminEmail      = $adminEmail;
        $this->translator      = $translator;
    }

    /**
     * @throws MissingRequiredMIMEParameters
     */
    public function sendFeedback(string $email, string $feedback)
    {
        $this->mailer->sendMessage($this->domain, [
            'from'    => $this->from,
            'to'      => $this->adminEmail,
            'subject' => $this->translator->trans('Adopta un suflet - Feedback Nou'),
            'html'    => '
               Ai un feedback nou. <br />
               Email: ' . $email . ',  <br />
               Message: ' . $feedback . '
            ',
        ]);
    }

    /**
     * @throws MissingRequiredMIMEParameters
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendCreateMessage(User $user, Classified $classified)
    {
        if (!$user->getEmail()) {
            return;
        }

        $this->mailer->sendMessage($this->domain, [
            'from'    => $this->from,
            'to'      => $user->getEmail(),
            'subject' => $this->translator->trans('Adopta un suflet - Anunt nou'),
            'html'    => $this->twigEnvironment->render('Email/new_add.html.twig', [
                'classified' => $classified,
                'user'       => $user,
            ]),
        ]);
    }

    /**
     * @throws MissingRequiredMIMEParameters
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyAdmin(User $user, Classified $classified)
    {
        $this->mailer->sendMessage($this->domain, [
            'from'    => $this->from,
            'to'      => $this->adminEmail,
            'subject' => $this->translator->trans('Adopta un suflet - Anunt nou'),
            'html'    => $this->twigEnvironment->render('Email/new_add_admin.html.twig', [
                'classified' => $classified,
                'user'       => $user,
            ]),
        ]);
    }

    /**
     * @param User $user
     * @param Classified $classified
     *
     * @throws MissingRequiredMIMEParameters
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyUserWithReminder(User $user, Classified $classified)
    {
        $user->setEmail('enachetudorel@gmail.com');
        $this->mailer->sendMessage($this->domain, [
            'from'    => $this->from,
            'to'      => $user->getEmail(),
            'subject' => $this->translator->trans('Reminder to update classified status'),
            'html'    => $this->twigEnvironment->render('Email/user_reminder.html.twig', [
                'classified' => $classified,
                'user'       => $user,
            ]),
        ]);
    }
}
