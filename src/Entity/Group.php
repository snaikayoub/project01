<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`groupe`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: User::class)]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groupesGeres')]
    private Collection $gestionnaires;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->gestionnaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGroupe($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGroupe() === $this) {
                $user->setGroupe(null);
            }
        }

        return $this;
    }

    
    public function __toString()
    {
       return $this->nom;
    }

    /**
     * @return Collection<int, User>
     */
    public function getGestionnaires(): Collection
    {
        return $this->gestionnaires;
    }

    public function addGestionnaire(User $gestionnaire): self
    {
        if (!$this->gestionnaires->contains($gestionnaire)) {
            $this->gestionnaires->add($gestionnaire);
            $gestionnaire->addGroupesGere($this);
        }

        return $this;
    }

    public function removeGestionnaire(User $gestionnaire): self
    {
        if ($this->gestionnaires->removeElement($gestionnaire)) {
            $gestionnaire->removeGroupesGere($this);
        }

        return $this;
    }

}
