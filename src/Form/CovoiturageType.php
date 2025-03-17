<?php

namespace App\Form;

use App\Entity\Covoiturage;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_depart',  DateTimeType::class)
            ->add('heure_depart', DateTimeType::class)
            ->add('date_arrivee', DateTimeType::class)
            ->add('heure_arrivee', TextType::class)
            ->add('lieu_depart', TextType::class)
            ->add('lieu_arrivee', TextType::class)
            ->add('Statut', TextType::class)
            ->add('nb_place', TextType::class)
            ->add('prix_personne', TextType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
        ]);
    }
}