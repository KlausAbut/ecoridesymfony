<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Covoiturage::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Covoiturage $covoiturage = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateParticipation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCovoiturage(): ?Covoiturage
    {
        return $this->covoiturage;
    }

    public function setCovoiturage(?Covoiturage $covoiturage): self
    {
        $this->covoiturage = $covoiturage;
        return $this;
    }

    public function getDateParticipation(): ?\DateTimeInterface
    {
        return $this->dateParticipation;
    }

    public function setDateParticipation(\DateTimeInterface $dateParticipation): self
    {
        $this->dateParticipation = $dateParticipation;
        return $this;
    }
}
