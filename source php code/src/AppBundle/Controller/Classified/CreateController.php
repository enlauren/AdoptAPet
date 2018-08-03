<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller\Classified;

use App\AppBundle\Application\Command\CreateClassifiedCommand;
use App\AppBundle\Application\Handler\CreateClassifiedHandler;
use App\AppBundle\Entity\Classified;
use App\AppBundle\Form\ClassifiedType;
use Doctrine\ORM\ORMException;
use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function var_dump;

class CreateController extends Controller
{
    /**
     * @var CreateClassifiedHandler
     */
    private $createClassifiedHandler;

    public function __construct(CreateClassifiedHandler $createClassifiedHandler)
    {
        $this->createClassifiedHandler = $createClassifiedHandler;
    }

    /**
     * @Route("adauga/anunt", name="classified.new")
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $classified = new Classified();

        $form = $this->createForm(ClassifiedType::class, $classified);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $command = new CreateClassifiedCommand($classified);
                $this->createClassifiedHandler->handle($command);

                return $this->redirectToRoute('classified.single', [
                        'slug' => $classified->getSlug(),
                    ]
                );
            } catch (ORMException $e) {
                $this->get('logger')->error('Unable to save classified:', [
                    'exception'  => $e,
                    'classified' => $classified,
                ]);

                $this->get('session')
                    ->getFlashBag()
                    ->add('error', 'You already added this classified.');
            } catch (MissingRequiredMIMEParameters $e) {
                dump($e);
            } catch (\Twig_Error_Loader $e) {
                dump($e);
            } catch (\Twig_Error_Runtime $e) {
                dump($e);
            } catch (\Twig_Error_Syntax $e) {
                dump($e);
            }
        }

        return $this->render('@AppBundle/Classified/create.html.twig', [
            'title' => $this->get('translator')->trans('title_create_classified'),
            'form' => $form->createView(),
        ]);
    }
}
