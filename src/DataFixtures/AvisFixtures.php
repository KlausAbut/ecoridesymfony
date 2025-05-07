<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupère tous les users existants (si créés dans d'autres fixtures)
        $users = $manager->getRepository(User::class)->findAll();

        if (empty($users)) {
            return; // on sort si aucun utilisateur
        }

        $avisData = [
            "Très bonne expérience, je recommande !" => '5',
            "Ponctuel et sympathique." => '4',
            "Conduite agréable et voiture propre." => '5',
            "Un peu de retard au départ." => '3',
            "Top, merci encore pour le trajet !" => '5',
        ];

        $i = 0;
        foreach ($avisData as $contenu => $note) {
            $avis = new Avis();
            $avis->setUser($users[$i % count($users)]);
            $avis->setCommentaire($contenu);
            $avis->setNote($note);
            $avis->setStatut('VALIDÉ');
            $manager->persist($avis);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class, // ou le nom de ta fixture User
        ];
    }
}
