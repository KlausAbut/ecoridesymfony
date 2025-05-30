<?php

namespace App\DataFixtures;

use App\Entity\Participation;
use App\Entity\Covoiturage;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ParticipationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

       $allUsers = $manager->getRepository(User::class)->findAll();
        $users = array_filter(
            $allUsers,
            fn(User $u) => in_array('ROLE_USER', $u->getRoles(), true)
        );
        $covoiturages = $manager->getRepository(Covoiturage::class)->findAll();

        if (count($users) === 0 || count($covoiturages) === 0) {
            return;
        }

        foreach ($covoiturages as $covoit) {
            $places = $covoit->getNbPlace();
            if ($places <= 0) continue;

            $nbParticipants = $faker->numberBetween(0, min(2, $places));
            $participants = $faker->randomElements($users, $nbParticipants);

            foreach ($participants as $participant) {
                $participation = new Participation();
                $participation->setUser($participant);
                $participation->setCovoiturage($covoit);
                $participation->setDateParticipation($faker->dateTimeBetween('-1 months', 'now'));
                $manager->persist($participation);
                $covoit->setNbPlace($covoit->getNbPlace() - 1);
            }
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
