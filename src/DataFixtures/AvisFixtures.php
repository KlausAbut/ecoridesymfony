<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Covoiturage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        dump('AvisFixtures exécutée');

        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $users = array_filter($users, fn($u) => in_array('ROLE_USER', $u->getRoles()));
        $covoiturages = $manager->getRepository(Covoiturage::class)->findAll();

        dump('Nb users : ' . count($users));
        dump('Nb covoiturages : ' . count($covoiturages));

        if (count($users) === 0 || count($covoiturages) === 0) return;

        for ($i = 0; $i < 30; $i++) {
            $avis = new Avis();
            $avis->setUser($faker->randomElement($users));
            $avis->setCommentaire($faker->sentence(8));
            $avis->setNote($faker->numberBetween(3, 5));
            $avis->setCovoiturage($faker->randomElement($covoiturages));
            $avis->setStatut($i < 15 ? 'valide' : 'EN_ATTENTE');
            $manager->persist($avis);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
