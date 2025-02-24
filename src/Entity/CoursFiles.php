<?php

namespace App\Entity;

use App\Repository\CoursFilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursFilesRepository::class)]
class CoursFiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Cours::class, inversedBy: 'coursFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $coursfile = null;

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

    public function getCoursfile(): ?Cours
    {
        return $this->coursfile;
    }

    public function setCoursfile(?Cours $coursfile): self
    {
        $this->coursfile = $coursfile;
        return $this;
    }
}