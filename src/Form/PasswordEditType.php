<?php

declare(strict_types=1);

namespace Apb\UserBundle\Form;

use Apb\UserBundle\Enum\ErrorEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class PasswordEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL),
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => ErrorEnum::ERROR_PASSWORD_NOT_MATCH,
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL),
                    new Length(min: 8, minMessage: ErrorEnum::CONSTRAINT_RANGE_MIN),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
