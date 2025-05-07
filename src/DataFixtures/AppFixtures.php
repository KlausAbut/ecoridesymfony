<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Voiture;
use App\Entity\Covoiturage;
use App\Entity\Participation;
use App\Enum\CovoiturageStatut;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $lieux = [
            ['Paris', 'Lyon'],
            ['Marseille', 'Nice'],
            ['Bordeaux', 'Toulouse'],
            ['Lille', 'Strasbourg'],
            ['Nantes', 'Rennes']
        ];
        
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername("user$i")
                 ->setFirstname("Prenom$i")
                 ->setLastname("Nom$i")
                 ->setEmail("user$i@example.com")
                 ->setAdresse("Paris")
                 ->setTelephone("060000000$i")
                 ->setDateNaissance('1990-01-01')
                 ->setRoles(['ROLE_USER'])
                 ->setPhoto(null)
                 ->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        
            $voiture = new Voiture();
            $voiture->setUser($user)
                    ->setModele("Modèle$i")
                    ->setImmatriculation("AB-123-$i")
                    ->setEnergie($i % 2 === 0 ? 'électrique' : 'diesel')
                    ->setCouleur("Gris")
                    ->setDatePremiereImmatriculation('2020-01-01');
            $manager->persist($voiture);
        
            [$depart, $arrivee] = $lieux[$i - 1];
            $randomDate = new \DateTime("+{$i} days");
            $randomHour = (new \DateTime())->setTime(mt_rand(6, 10), 0);
        
            $covoiturage = new Covoiturage();
            $covoiturage->setCreatedBy($user)
                        ->setVoiture($voiture)
                        ->setLieuDepart($depart)
                        ->setLieuArrivee($arrivee)
                        ->setDateDepart($randomDate)
                        ->setHeureDepart($randomHour)
                        ->setDateArrivee(clone $randomDate)
                        ->setHeureArrivee((clone $randomHour)->modify('+3 hours'))
                        ->setNbPlace(3)
                        ->setPrixPersonne(mt_rand(15, 45))
                        ->setStatut(CovoiturageStatut::PUBLISHED)
                        ->setPublishedAt(new \DateTime());
            $manager->persist($covoiturage);
        }

        for ($j = 1; $j <= 3; $j++) {
            $participant = new User();
            $participant->setUsername("participant{$i}_{$j}")
                        ->setFirstname("PartPrenom{$i}_{$j}")
                        ->setLastname("PartNom{$i}_{$j}")
                        ->setEmail("participant{$i}_{$j}@example.com")
                        ->setAdresse("Ville")
                        ->setTelephone("07000000{$i}{$j}")
                        ->setDateNaissance('1995-01-01')
                        ->setRoles(['ROLE_USER'])
                        ->setPhoto(null)
                        ->setPassword($this->passwordHasher->hashPassword($participant, 'password'));
            $manager->persist($participant);
        
            $participation = new Participation();
            $participation->setUser($participant)
                          ->setCovoiturage($covoiturage)
                          ->setDateParticipation(new \DateTime());
            $manager->persist($participation);
        }
        
        $manager->flush();
    }
}