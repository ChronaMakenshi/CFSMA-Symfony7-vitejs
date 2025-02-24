<?php

namespace App\Entity;

use App\Repository\SectionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SectionsRepository::class)]
class Sections
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 2, max: 20, minMessage: 'Votre message doit avoir au moins {{ limit }} caractères', maxMessage: 'Votre message doit avoir au plus {{ limit }} caractères')]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Compagnies::class, inversedBy: 'sections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compagnies $compagnie;

    #[ORM\OneToMany(targetEntity: Filieres::class, mappedBy: 'section', cascade: ['remove'])]
    private Collection $filieres;

    #[ORM\OneToMany(targetEntity: Classes::class, mappedBy: 'section')]
    private Collection $classes;

    public function __construct()
    {
        $this->filieres = new ArrayCollection();
        $this->classes = new ArrayCollection();
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

    public function getCompagnie(): ?Compagnies
    {
        return $this->compagnie;
    }

    public function setCompagnie(?Compagnies $compagnie): self
    {
        $this->compagnie = $compagnie;
        return $this;
    }

    public function getFilieres(): Collection
    {
        return $this->filieres;
    }

    public function addFiliere(Filieres $filiere): self
    {
        if (!$this->filieres->contains($filiere)) {
            $this->filieres[] = $filiere;
            $filiere->setSection($this);
        }

        return $this;
    }

    public function removeFiliere(Filieres $filiere): self
    {
        if ($this->filieres->removeElement($filiere)) {
            if ($filiere->getSection() === $this) {
                $filiere->setSection(null);
            }
        }

        return $this;
    }

    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClasse(Classes $classe): self
    {
        if (!$this->classes->contains($classe)) {
            $this->classes[] = $classe;
            $classe->setSection($this);
        }

        return $this;
    }

    public function removeClasse(Classes $classe): self
    {
        if ($this->classes->removeElement($classe)) {
            if ($classe->getSection() === $this) {
                $classe->setSection(null);
            }
        }

        return $this;
    }
}
