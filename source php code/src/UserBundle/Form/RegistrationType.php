<?php
declare(strict_types = 1);

namespace App\UserBundle\Form;

use App\UserBundle\Entity\Repository\UserRepository;
use App\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                    'label'              => 'form.email',
                    'translation_domain' => 'validators',
                ]
            )
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'first_options'   => ['label' => 'form.password'],
                'second_options'  => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'form.password_missmatch',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => User::class,
            'csrf_token_id' => 'registration',
        ]);
    }
}
