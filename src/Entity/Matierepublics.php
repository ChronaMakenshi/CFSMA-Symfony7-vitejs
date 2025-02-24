<?php

namespace App\Entity;

use App\Repository\MatierepublicsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MatierepublicsRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Cette matière est déjà présente')]
class Matierepublics
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

    #[ORM\OneToMany(targetEntity: Courpublics::class, mappedBy: 'matierepublic', orphanRemoval: true, cascade: ['remove'])]
    private Collection $courpublics;

    public function __construct()
    {
        $this->courpublics = new ArrayCollection();
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

    /**
     * @return Collection|Courpublics[]
     */
    public function getCourpublics(): Collection
    {
        return $this->courpublics;
    }

    public function addCourpublic(Courpublics $courpublic): self
    {
        if (!$this->courpublics->contains($courpublic)) {
            $this->courpublics[] = $courpublic;
            $courpublic->setMatierepublic($this);
        }

        return $this;
    }

    public function removeCourpublic(Courpublics $courpublic): self
    {
        if ($this->courpublics->removeElement($courpublic)) {
            if ($courpublic->getMatierepublic() === $this) {
                $courpublic->setMatierepublic(null);
            }
        }

        return $this;
    }
}
