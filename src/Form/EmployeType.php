<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('username', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('dateNaissance', TextType::class, [
                'label' => 'Date de naissance',
                'constraints' => [new NotBlank()],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [new NotBlank()],
                'label' => 'Mot de passe'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
