<?php

namespace App\Document;

use App\Entity\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\UserCreditRepository;

#[MongoDB\Document(repositoryClass: UserCreditRepository::class)]
class UserCredit 
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type:'int')]
    private int $userId;

    #[MongoDB\Field(type: 'int')]
    private $amount = 20;

    #[MongoDB\Field(type: 'date')]
    private $lastUpdated;

    // Getters/Setters
   
    public function addCredit(int $value): void
    {
        $this->amount += $value;
        $this->lastUpdated = new \DateTime();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of user
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Set the value of lastUpdated
     */
    public function setLastUpdated($lastUpdated): self
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

     /**
     * Get the value of amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     */
    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    #[MongoDB\Field(type: 'collection')]
    private array $transactions = [];

    public function logTransaction(string $type, int $amount): void
    {
        $this->transactions[] = [
            'type' => $type, // "purchase", "ride_payment", "refund"
            'amount' => $amount,
            'date' => new \DateTime()
        ];
    }

    public static function createForUser(User $user): self
    {
        $credit = new self();
        $credit->setUserId($user->getId());
        $credit->setAmount(20); // Crédit initial
        return $credit;
    }
}
   

