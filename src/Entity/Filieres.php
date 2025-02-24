<?php

namespace App\Entity;

use App\Repository\FilieresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilieresRepository::class)]
class Filieres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Assert\Length(min: 3, max: 20, minMessage: 'Votre message a moins que {{ limit }} caractères', maxMessage: 'Votre message a plus que {{ limit }} caractères')]
    private $name;

    #[ORM\ManyToOne(targetEntity: Sections::class, inversedBy: 'filieres')]
    #[ORM\JoinColumn(nullable: false)]
    private $section;

    #[ORM\OneToMany(targetEntity: Classes::class, mappedBy: 'filiere', cascade: ['remove'])]
    private $classes;

    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'filiere', orphanRemoval: true)]
    private $users;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $formateur;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classes $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setFiliere($this);
        }

        return $this;
    }

    public function removeClass(Classes $class): self
    {
        if ($this->classes->removeElement($class)) {
            if ($class->getFiliere() === $this) {
                $class->setFiliere(null);
            }
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setFiliere($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            if ($user->getFiliere() === $this) {
                $user->setFiliere(null);
            }
        }

        return $this;
    }

    public function getFormateur(): ?Users
    {
        return $this->formateur;
    }

    public function setFormateur(?Users $formateur): self
    {
        $this->formateur = $formateur;
        return $this;
    }
}
