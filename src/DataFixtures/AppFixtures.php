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
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];
        $chauffeurs = [];
        $lieux = ['Paris','Lyon','Marseille','Nice','Bordeaux','Toulouse','Lille','Strasbourg','Nantes','Rennes','Madrid','Barcelone','Rome','Berlin','Bruxelles','Amsterdam','Zurich','Lisbonne','Vienne','Prague'];

        // 👤 50 utilisateurs normaux
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setUsername("user$i")
                 ->setFirstname($faker->firstName)
                 ->setLastname($faker->lastName)
                 ->setEmail("user$i@example.com")
                 ->setAdresse($faker->city)
                 ->setTelephone($faker->phoneNumber)
                 ->setDateNaissance($faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'))
                 ->setRoles(['ROLE_USER'])
                 ->setPhoto(null)
                 ->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }

        // 👨‍✈️ 20 conducteurs avec voitures et covoiturages
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername("chauffeur$i")
                 ->setFirstname($faker->firstName)
                 ->setLastname($faker->lastName)
                 ->setEmail("chauffeur$i@example.com")
                 ->setAdresse($faker->city)
                 ->setTelephone($faker->phoneNumber)
                 ->setDateNaissance($faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'))
                 ->setRoles(['ROLE_USER'])
                 ->setPhoto(null)
                 ->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $chauffeurs[] = $user;
            $users[] = $user;

            $voiture = new Voiture();
            $voiture->setUser($user)
                    ->setModele($faker->word)
                    ->setImmatriculation(strtoupper($faker->bothify('??-###-??')))
                    ->setEnergie($faker->randomElement(['électrique', 'diesel', 'essence']))
                    ->setCouleur($faker->safeColorName)
                    ->setDatePremiereImmatriculation($faker->dateTimeBetween('-10 years', '-1 year')->format('Y-m-d'));
            $manager->persist($voiture);

            // 🚗 5 covoiturages par chauffeur → total 100
            for ($j = 0; $j < 5; $j++) {
                [$depart, $arrivee] = $faker->randomElements($lieux, 2);
                $date = $faker->dateTimeBetween('+1 days', '+2 months');
                $heure = (clone $date)->setTime(mt_rand(6, 20), 0);

                $covoiturage = new Covoiturage();
                $covoiturage->setCreatedBy($user)
                            ->setVoiture($voiture)
                            ->setLieuDepart($depart)
                            ->setLieuArrivee($arrivee)
                            ->setDateDepart($date)
                            ->setHeureDepart($heure)
                            ->setDateArrivee($date)
                            ->setHeureArrivee((clone $heure)->modify('+3 hours'))
                            ->setNbPlace(3)
                            ->setPrixPersonne(mt_rand(10, 40))
                            ->setStatut(CovoiturageStatut::PUBLISHED)
                            ->setPublishedAt(new \DateTime());
                $manager->persist($covoiturage);
            }
        }

        // 👨‍💼 2 employés
        for ($i = 1; $i <= 2; $i++) {
            $employee = new User();
            $employee->setUsername("employee$i")
                     ->setFirstname("Employé$i")
                     ->setLastname("Support")
                     ->setEmail("employee$i@ecoride.com")
                     ->setAdresse("Paris")
                     ->setTelephone("061122334$i")
                     ->setDateNaissance('1985-01-01')
                     ->setRoles(['ROLE_EMPLOYE'])
                     ->setPassword($this->passwordHasher->hashPassword($employee, 'password'));
            $manager->persist($employee);
        }

        // 👑 1 admin
        $admin = new User();
        $admin->setUsername('admin')
              ->setFirstname('Admin')
              ->setLastname('Admin')
              ->setEmail('admin@admin.com')
              ->setAdresse('Paris')
              ->setTelephone('0600000000')
              ->setDateNaissance('1990-01-01')
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'))
              ->setPhoto(null);
        $manager->persist($admin);

        $manager->flush();
    }
}
