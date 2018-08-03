<?php
declare(strict_types = 1);

namespace App\AppBundle\Form;

use App\AppBundle\Entity\City;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * TODO find a way to test this form type:
 *      TypeError: Argument 1 passed to App\AppBundle\Form\PetMedicalCenterType::__construct()
 *      must be an instance of Doctrine\ORM\EntityManager, none given
 *
 * @package App\AppBundle\Form
 */
class PetMedicalCenterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$builder
	    ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('address', TextareaType::class)
            ->add('schedule', TextareaType::class)
	    ->add('website', TextType::class)
            ->add('phone', TextType::class)
            ->add('email', TextType::class)
	    ->add('nonstop', CheckboxType::class, [
	    	'required' => false,
	    ])
            ->add('city', EntityType::class, [
                'class'         => City::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }
}
