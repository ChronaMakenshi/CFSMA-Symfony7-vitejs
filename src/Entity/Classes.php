<?php

namespace App\Entity;

use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClassesRepository::class)]
class Classes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'Votre message a moins que {{ limit }} caractères',
        maxMessage: 'Votre message a plus que {{ limit }} caractères'
    )]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Filieres::class, inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filieres $filiere = null;

    #[ORM\ManyToOne(targetEntity: Sections::class, inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Sections $section = null;

    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'classe', cascade: ['remove'])]
    private Collection $cours;

    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'classe', orphanRemoval: true)]
    private Collection $users;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getFiliere(): ?Filieres
    {
        return $this->filiere;
    }

    public function setFiliere(?Filieres $filiere): self
    {
        $this->filiere = $filiere;
        return $this;
    }

    public function getSection(): ?Sections
    {
        return $this->section;
    }

    public function setSection(?Sections $section): self
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setClasse($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            if ($cour->getClasse() === $this) {
                $cour->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClasse($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            if ($user->getClasse() === $this) {
                $user->setClasse(null);
            }
        }

        return $this;
    }
}
