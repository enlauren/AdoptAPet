<?php
declare(strict_types = 1);

namespace App\AppBundle\Form;

use App\AppBundle\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Form\UserEmailType;

class FeedbackType extends AbstractType
{
    /**
     * TODO: translate placeholders and labels
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserEmailType::class)
            ->add('content', TextareaType::class, [
                'label' => 'Mesaj',
                'attr'  => [
                    'placeholder' => 'Mesaj',
                    'class'       => 'form-control',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => Feedback::class,
            'csrf_protection' => false,
        ]);
    }
}
