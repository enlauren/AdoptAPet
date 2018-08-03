<?php
declare(strict_types = 1);

namespace App\AppBundle\Form;


use App\AppBundle\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    /**
     * TODO: translate placeholders and labels
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Emailul tau',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Emailul tau',
                    'class'       => 'form-control',
                ],
            ])
            ->add('phone', NumberType::class, [
                'label' => 'Numarul tau de telefon',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Numarul tau de telefon',
                    'class'       => 'form-control',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Mesajul tau',
                'attr' => [
                    'placeholder' => 'Mesajul tau',
                    'class' => 'form-control'
                ]
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'message_type';
    }
}
