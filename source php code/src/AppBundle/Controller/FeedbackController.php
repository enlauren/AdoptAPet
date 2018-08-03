<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Feedback;
use App\AppBundle\Form\FeedbackType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends Controller
{
    /**
     * @Route("/feedback/add", name="feedback.add")
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $feedback = $this->get('factory.feedback')->create();

        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // todo this logic can be extracted into a resolver
            $feedback->setUser(
                $this->get('user_bundle.provider.user')->get($feedback->getUser())
            );

            $this->get('repository.feedback')->save($feedback);
            $this->get('pets_mailer')->sendFeedback($feedback->getUser()->getEmail(), $feedback->getContent());

            return new JsonResponse(['ok' => 1]);
        }

        return new JsonResponse(['ok' => 0]);
    }
}
