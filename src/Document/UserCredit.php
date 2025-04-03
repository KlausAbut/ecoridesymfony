<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="user_credit")
 */
class UserCredit
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * L'identifiant de l'utilisateur dans ta base relationnelle.
     * 
     * @MongoDB\Field(type="string")
     */
    private $userId;

    /**
     * Le crédit attribué à l'utilisateur.
     * 
     * @MongoDB\Field(type="int")
     */
    private $credit;

    public function __construct(string $userId, int $credit = 20)
    {
        $this->userId = $userId;
        $this->credit = $credit;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): self
    {
        $this->credit = $credit;
        return $this;
    }
}
