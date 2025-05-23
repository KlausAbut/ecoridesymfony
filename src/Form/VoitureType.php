<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('modele', TextType::class)
            ->add('immatriculation', TextType::class)
            ->add('energie', TextType::class)
            ->add('couleur', TextType::class)
            ->add('date_premiere_immatriculation', TextType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}