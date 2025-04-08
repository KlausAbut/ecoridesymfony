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
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername("user$i");
            $user->setFirstname("Prenom$i");
            $user->setLastname("Nom$i");
            $user->setEmail("user$i@example.com");
            $user->setAdresse("Paris");
            $user->setTelephone("060000000$i");
            $user->setDateNaissance('1990-01-01');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $user->setPhoto(null);
            $manager->persist($user);

            $voiture = new Voiture();
            $voiture->setUser($user);
            $voiture->setModele("Modèle$i");
            $voiture->setImmatriculation("AB-123-$i");
            $voiture->setEnergie($i % 2 === 0 ? 'électrique' : 'diesel');
            $voiture->setCouleur("Gris");
            $voiture->setDatePremiereImmatriculation('2020-01-01');
            $manager->persist($voiture);

            $covoiturage = new Covoiturage();
            $covoiturage->setCreatedBy($user);
            $covoiturage->setVoiture($voiture);
            $covoiturage->setLieuDepart("Paris");
            $covoiturage->setLieuArrivee("Lyon");
            $covoiturage->setDateDepart(new \DateTime('+1 day'));
            $covoiturage->setHeureDepart(new \DateTime('08:00'));
            $covoiturage->setDateArrivee(new \DateTime('+1 day'));
            $covoiturage->setHeureArrivee(new \DateTime('12:00'));
            $covoiturage->setNbPlace(3);
            $covoiturage->setPrixPersonne(25);
            $covoiturage->setStatut(CovoiturageStatut::DRAFT);
            $covoiturage->setPublishedAt(new \DateTime());
            $manager->persist($covoiturage);

            // Participations factices
            if ($i > 1) {
                $participant = new Participation();
                $participant->setUser($user);
                $participant->setCovoiturage($covoiturage);
                $participant->setDateParticipation(new \DateTime());
                $manager->persist($participant);
            }
        }

        $manager->flush();
    }
}