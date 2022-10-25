<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\File;
use	App\Listeners\Doctrine\UserListener;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners([UserListener::class])]

#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Group $groupe = null;

    #[ORM\Column( nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'user_images', fileNameProperty: 'photo')]
    private ?File $photoFile = null;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'gestionnaires')]
    private Collection $groupesGeres;

    public function __construct()
    {
        $this->groupesGeres = new ArrayCollection();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $photoFile
     */
    
   
    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if (null !== $photoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getGroupe(): ?Group
    {
        return $this->groupe;
    }

    public function setGroupe(?Group $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
    
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupesGeres(): Collection
    {
        return $this->groupesGeres;
    }

    public function addGroupesGere(Group $groupesGere): self
    {
        if (!$this->groupesGeres->contains($groupesGere)) {
            $this->groupesGeres->add($groupesGere);
        }

        return $this;
    }

    public function removeGroupesGere(Group $groupesGere): self
    {
        $this->groupesGeres->removeElement($groupesGere);

        return $this;
    }
    
    public function __toString()
    {
        return $this->getEmail();
    }
}