<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Classified;
use App\AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MessageController extends Controller
{
    /**
     * @Route("/ajax/message/{id}/send")
     * @ParamConverter("classified", class="App\AppBundle\Entity\Classified")
     *
     * @param Request    $request
     * @param Classified $classified
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, Classified $classified)
    {
        $message = $this->get('factory.message')->create();
        $form    = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // todo move logic to service
            $message->setClassified($classified);

            $email = $form->get('email')->getData();
            $phone = $form->get('phone')->getData();

            $user = $this->get('user_bundle.repository.user')->findOneBy(['email' => $email]);

            if (null === $user) {
                $user = $this->get('user_bundle.factory.user')
                    ->createUserFromEmailAndPhone(
                        $email,
                        $phone
                    );
            }

            $message->setAuthor($user);

            $this->get('user_bundle.repository.user')->save($user);
            $this->get('repository.message')->save($message, true);

            // todo send email to author of the ad

            return new JsonResponse((array)$message);
        }

        return new JsonResponse([
            'invalid',
            'errors' => $form->getErrors()
        ]);
    }
}
