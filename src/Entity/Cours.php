<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Matieres::class, inversedBy: 'cours')]
    private ?Matieres $matiere = null;

    #[ORM\ManyToOne(targetEntity: Classes::class, inversedBy: 'cours')]
    private ?Classes $classe = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(targetEntity: CoursFiles::class, mappedBy: 'coursfile', orphanRemoval: true, cascade: ['persist'])]
    private Collection $coursFiles;

    public function __construct()
    {
        $this->coursFiles = new ArrayCollection();
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

    public function getMatiere(): ?Matieres
    {
        return $this->matiere;
    }

    public function setMatiere(?Matieres $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getClasse(): ?Classes
    {
        return $this->classe;
    }

    public function setClasse(?Classes $classe): self
    {
        $this->classe = $classe;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Collection|CoursFiles[]
     */
    public function getCoursFiles(): Collection
    {
        return $this->coursFiles;
    }

    public function addCoursFile(CoursFiles $coursFile): self
    {
        if (!$this->coursFiles->contains($coursFile)) {
            $this->coursFiles[] = $coursFile;
            $coursFile->setCoursfile($this);
        }

        return $this;
    }

    public function removeCoursFile(CoursFiles $coursFile): self
    {
        if ($this->coursFiles->removeElement($coursFile)) {
            if ($coursFile->getCoursfile() === $this) {
                $coursFile->setCoursfile(null);
            }
        }

        return $this;
    }
}
