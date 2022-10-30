<?php

namespace App\Entity;

use App\Repository\AstreinteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AstreinteRepository::class)]
class Astreinte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'astreints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $relatedMonth = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?User
    {
        return $this->matricule;
    }

    public function setMatricule(?User $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getRelatedMonth(): ?string
    {
        return $this->relatedMonth;
    }

    public function setRelatedMonth(string $relatedMonth): self
    {
        $this->relatedMonth = $relatedMonth;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }
}
