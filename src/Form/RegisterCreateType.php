<?php

declare(strict_types=1);

namespace Apb\UserBundle\Form;

use Apb\UserBundle\Entity\User;
use Apb\UserBundle\Enum\ErrorEnum;
use Apb\UserBundle\Enum\UserEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class RegisterCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL),
                    new Email(message: ErrorEnum::CONSTRAINT_INVALID_EMAIL),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => ErrorEnum::ERROR_PASSWORD_NOT_MATCH,
                'options' => [
                    'attr' => [
                        'class' => 'password-field',
                    ],
                ],
                'required' => true,
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL),
                ],
            ])
            ->add('consents', ChoiceType::class, [
                'multiple' => true,
                'choices' => UserEnum::getConsentChoices(),
                'invalid_message' => ErrorEnum::INVALID_CHOICE,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
