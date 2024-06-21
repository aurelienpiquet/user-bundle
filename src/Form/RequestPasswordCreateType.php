<?php

declare(strict_types=1);

namespace Apb\UserBundle\Form;

use Apb\UserBundle\Enum\ErrorEnum;
use Apb\UserBundle\Model\ResetPasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class RequestPasswordCreateType extends AbstractType
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
            ->add('redirect', TextType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'class' => ResetPasswordModel::class,
        ]);
    }
}
