<?php

namespace App\Entity;

use App\Repository\CompagniesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompagniesRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Cette section est déjà présente')]
class Compagnies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Votre message a moins que {{ limit }} caractères',
        maxMessage: 'Votre message a plus que {{ limit }} caractères'
    )]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Sections::class, mappedBy: 'compagnie', cascade: ['remove'])]
    private Collection $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
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

    /**
     * @return Collection|Sections[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Sections $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setCompagnie($this);
        }

        return $this;
    }

    public function removeSection(Sections $section): self
    {
        if ($this->sections->removeElement($section)) {
            if ($section->getCompagnie() === $this) {
                $section->setCompagnie(null);
            }
        }

        return $this;
    }
}
