<?php

namespace App\Entity;

use App\Entity\ImageDefault;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImagesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Galery $galery = null;


    public function __construct()
    {
        $this->drivers = new ArrayCollection();
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

    public function getGalery(): ?Galery
    {
        return $this->galery;
    }

    public function setGalery(?Galery $galery): self
    {
        $this->galery = $galery;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
