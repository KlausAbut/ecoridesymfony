<?php

namespace App\Form;

use App\Entity\Voiture;
use App\Entity\Covoiturage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\CovoiturageStatut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_depart', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('heure_depart', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_arrivee', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('heure_arrivee', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieu_depart', TextType::class)
            ->add('lieu_arrivee', TextType::class)
            ->add('nb_place', TextType::class)
            ->add('prix_personne', TextType::class)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Brouillon' => CovoiturageStatut::DRAFT,
                    'Publié' => CovoiturageStatut::PUBLISHED,
                    'Annulé' => CovoiturageStatut::CANCELLED,
                ],
                'label' => 'Statut',
            ])
            ->add('voiture', EntityType::class, [
                'class' => Voiture::class,
                'choice_label' => function (Voiture $voiture) {
                    return $voiture->getModele() . ' – ' . $voiture->getImmatriculation();
                },
                'label' => 'Voiture utilisée',
            ])
            ->add('participants', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
            'user' => null,
        ]);
    }
}
