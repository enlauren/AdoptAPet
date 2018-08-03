<?php

namespace App\AppBundle\Controller\Classified;

use App\AppBundle\Entity\Repository\ClassifiedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\AppBundle\Application\Command\CreateClassifiedCommand;
use App\AppBundle\Entity\Classified;
use App\AppBundle\Form\ClassifiedType;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;


class ActionsController extends Controller
{
    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;

    public function __construct(ClassifiedRepository $classifiedRepository)
    {

        $this->classifiedRepository = $classifiedRepository;
    }
    /**
     * @Route("classified/{id}/{token}/approve", name="classified.approve")
     * @param Request $request
     *
     * @return Response|RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function approveAction(Request $request, $id, $token)
    {
        /** @var Classified $classified */
        $classified = $this->classifiedRepository->findOneBy([
            'id' => $id,
            'token' => $token
        ]);

        if (!$classified) {
            throw new HttpException(404, 'Not found.');
        }

        $classified->setApproved(true);

        $this->classifiedRepository->save($classified);

        return $this->redirectToRoute('classified.single', [
            'slug' => $classified->getSlug(),
        ]);
    }

    /**
     * @Route("classified/{id}/{token}/reject", name="classified.reject")
     * 
     * @param int    $id
     * @param string $token
     * @return RedirectResponse|Response
     * @internal param Request $request
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function rejectAction($id, $token)
    {
        /** @var Classified $classified */
        $classified = $this->classifiedRepository->findOneBy([
            'id' => $id,
            'token' => $token
        ]);

        if (!$classified) {
            throw new HttpException(404, 'Not found.');
        }

        # only admin can delete ads
        if ($this->getUser() && $this->getUser()->getEmail() == 'enachetudorel@yahoo.com') {
            $classified->setDeletedAt(new \DateTime());
            $classifiedRepository->save($classified);
        }

        return $this->redirectToRoute('home');
    }
}
