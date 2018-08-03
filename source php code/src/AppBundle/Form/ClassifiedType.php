<?php
declare(strict_types = 1);

namespace App\AppBundle\Form;

use App\AppBundle\Entity\City;
use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Type;
use App\AppBundle\Services\Captcha\CaptchaValidatorInterface;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassifiedType extends AbstractType
{
    /**
     * @var CaptchaValidatorInterface
     */
    private $captchaValidator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param CaptchaValidatorInterface $captchaValidator
     * @param RequestStack              $requestStack
     */
    public function __construct(CaptchaValidatorInterface $captchaValidator, RequestStack $requestStack)
    {
        $this->captchaValidator = $captchaValidator;
        $this->requestStack = $requestStack;
    }

    /**
     * TODO: translate placeholders and labels
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titlu Anunt',
                'attr' => [
                    'placeholder' => 'Titlu Anunt',
                    'class'       => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descriere Anunt',
                'attr' => [
                    'placeholder' => 'Descriere',
                    'class'       => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresa Email',
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numar telefon',
                'attr' => [
                    'placeholder' => 'Numar telefon',
                    'class' => 'form-control'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Femela' => 'f',
                    'Mascul' => 'm'
                ],
                'label' => 'Gen',
                'attr' => [
                    'placeholder' => 'Gen',
                    'class' => 'form-control'
                ]
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'label' => 'Categorie Anunt',
                'attr' => [
                    'placeholder' => 'Tip',
                    'class' => 'form-control'
                ]
            ])
            ->add('cities', EntityType::class, [ // TODO fix this Nothing selected error
                'class' => City::class,
                'label' => 'Judet',
                'empty_data' => null,
                'placeholder' => 'Selecteaza un judet',
                'multiple' => true,
                'attr' => [
                    'placeholder' => 'Alege Judet',
                    'class' => 'form-control selectpicker'
                ]
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'label' => 'Imagini',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);

        $captchaValidator = function(FormEvent $event) {
            $captcha = $this->requestStack->getCurrentRequest()->get('g-recaptcha-response', '');
            if (true !== $this->captchaValidator->validate($captcha, $this->requestStack->getCurrentRequest()->getClientIp())) {
                $event->getForm()->addError(
                    new FormError('Captcha is not valid.')
                );
            }
        };

        $builder->addEventListener(FormEvents::POST_SUBMIT, $captchaValidator);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classified::class,
        ]);
    }
}
