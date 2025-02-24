<?php

namespace App\Entity;

use App\Repository\CoursFilespRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursFilespRepository::class)]
class CoursFilesp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Courpublics::class, inversedBy: 'coursFilesps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Courpublics $courfilesp = null;

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

    public function getCourfilesp(): ?Courpublics
    {
        return $this->courfilesp;
    }

    public function setCourfilesp(?Courpublics $courfilesp): self
    {
        $this->courfilesp = $courfilesp;
        return $this;
    }
}
