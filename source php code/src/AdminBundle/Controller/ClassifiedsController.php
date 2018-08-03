<?php
declare(strict_types=1);

namespace AdminBundle\Controller;

use AdminBundle\Model\StatusMap;
use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use function var_dump;

class ClassifiedsController extends Controller
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
     * @Route("/classifieds/list", name="admin.classifieds.list")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function classifiedsListAction(Request $request)
    {
        $status = $request->get('status');

        return [
            'classifieds' => $this->classifiedRepository->rawPage(
                $request->query->getInt('page', 1),
                $status
            ),
        ];
    }

    /**
     * @Route("/classifieds/{id}/edit", name="admin.classifieds.edit")
     * @Template()
     *
     * @param Request $request
     * @param int     $id
     * @return array|RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function classifiedEditAction(Request $request, $id)
    {
        /** @var Classified $classified */
        $classified = $this->classifiedRepository->find($id);
        $form       = $this->createFormBuilder($classified)
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('canonical', EntityType::class, [
                'class'         => Classified::class,
                'required'      => false,
                'choice_label'  => function (Classified $classified) {
                    return $classified->getId() . ' - ' . $classified->getTitle();
                },
                'query_builder' => function (ClassifiedRepository $classifiedRepository) use ($classified) {
                    return $classifiedRepository->createQueryBuilder('c')
                        ->andWhere('c.canonical is NULL')
                        ->andWhere('c.user = :user')
                        ->setParameter('user', $classified->getUser())
                        ->orderBy('c.createdAt', 'DESC');
                },
                'attr'          => [
                    'class'            => 'selectpicker',
                    'data-live-search' => 'true',
                ]
            ])
            ->add('description', TextareaType::class)
            ->add('user', EntityType::class, [
                'class' => 'UserBundle:User',
            ])
            ->add('expired', CheckboxType::class, [
                'required' => false,
            ])
            ->add('gender', TextType::class)
            ->add('priority', NumberType::class)
            ->add('approved', CheckboxType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->classifiedRepository->save($classified);
            $this->addFlash('success', 'Classified was saved.');

            return $this->redirectToRoute('admin.classifieds.list');
        }

        return [
            'classified'     => $classified,
            'classifiedForm' => $form->createView()
        ];
    }
}
