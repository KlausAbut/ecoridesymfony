<?php

namespace App\Command;

use App\Document\User;
use App\Document\UserCredit;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init-credits',
    description: 'Initialize credits for existing users'
)]
class InitCreditsCommand extends Command
{
    public function __construct(
        private DocumentManager $dm
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->dm->getRepository(User::class)->findAll();
        $created = 0;

        foreach ($users as $user) {
            if (!$user->getCredit()) {
                $credit = new UserCredit();
                $credit->setUser($user);
                $credit->setAmount(20); // Crédit initial selon l'énoncé
                
                $this->dm->persist($credit);
                $created++;
            }
        }

        $this->dm->flush();
        $output->writeln(sprintf('Created %d new credit accounts', $created));

        return Command::SUCCESS;
    }
}