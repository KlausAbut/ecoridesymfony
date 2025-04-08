<?php

namespace App\Repository;

use App\Document\UserCredit;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class UserCreditRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        parent::__construct($dm, $dm->getUnitOfWork(), $dm->getClassMetadata(UserCredit::class));
    }

    /**
     * Trouve le crédit d'un utilisateur avec verrouillage optimiste
     */
    public function findUserCreditWithLock(string $userId): ?UserCredit
    {
        return $this->createQueryBuilder()
            ->field('user.$id')->equals(new \MongoDB\BSON\ObjectId($userId))
            ->getQuery()
            ->execute()
            ->getSingleResult();
    }

    /**
     * Met à jour le solde de crédit
     */
    public function updateBalance(string $userId, int $amount): void
    {
        $this->createQueryBuilder()
            ->updateOne()
            ->field('user.$id')->equals(new \MongoDB\BSON\ObjectId($userId))
            ->field('amount')->inc($amount)
            ->field('lastUpdated')->set(new \DateTime())
            ->getQuery()
            ->execute();
    }
}