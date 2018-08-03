<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller\Classified;

use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use App\AppBundle\Entity\Repository\TypeRepository;
use App\AppBundle\Entity\Type;
use App\AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SingleController extends Controller
{
    /**
     * @Route("/{slug}", name="classified.single", requirements={
     *     "slug": "^(?!login$|register$|cabinete-veterinare$|cauta|despre-noi|contact$)[^ \/]+$"
     * })
     *
     * @ParamConverter("classified", class="App\AppBundle\Entity\Classified")
     * @Template()
     *
     * @param Request $request
     *
     * @param Classified $classified
     * @return Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function singleAction(Request $request, Classified $classified)
    {
        if ($classified->getDeletedAt()) {
            throw new HttpException(404, 'Classified not found.');
        }

        if (!$classified->isApproved()) {
            return $this->render('approve_required.html.twig');
        }

        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        $typeCat = $this->get(TypeRepository::class)->findOneBy(['slug' => Type::TYPE_CAT]);
        $typeDog = $this->get(TypeRepository::class)->findOneBy(['slug' => Type::TYPE_DOG]);

        $this->get(ClassifiedRepository::class)->incrementViews($classified);

        return $this->render('@AppBundle/Classified/Single/single.html.twig', [
            'classified'              => $classified,
            'title'                   => $classified->getTitle() . $this->get('translator')->trans('title_base'),
            'messageForm'             => $form->createView(),
            'allow_external_comments' => $this->getParameter('allow_disqusfb_comments'),
            'latestClassifieds'       => $this->get(ClassifiedRepository::class)->getLatest(),
            'relatedClassifiedsCats'  => $this->get(ClassifiedRepository::class)->getRelatedByCities($classified, $typeCat),
            'relatedClassifiedsDogs'  => $this->get(ClassifiedRepository::class)->getRelatedByCities($classified, $typeDog),
        ]);
    }
}
