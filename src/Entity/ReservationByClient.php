<?php

namespace App\Entity;

use App\Repository\ReservationByClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ReservationByClientRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    errorPath: 'name',
    message: 'Une reservation avec le meme nom existe dans la file '

)]
class ReservationByClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column(length: 255)]
    private ?string $starAdress = null;

    #[ORM\Column(length: 255)]
    private ?string $endAdress = null;

    #[ORM\Column(length: 255)]
    private ?string $service = null;

    #[ORM\Column(nullable: true)]
    private ?int $driverId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $nbPassagers = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbBagages = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numFlight = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $messages = null;

    #[ORM\Column(nullable: true)]
    private ?int $userId = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getStarAdress(): ?string
    {
        return $this->starAdress;
    }

    public function setStarAdress(string $starAdress): self
    {
        $this->starAdress = $starAdress;

        return $this;
    }

    public function getEndAdress(): ?string
    {
        return $this->endAdress;
    }

    public function setEndAdress(string $endAdress): self
    {
        $this->endAdress = $endAdress;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getDriverId(): ?int
    {
        return $this->driverId;
    }

    public function setDriverId(?int $driverId): self
    {
        $this->driverId = $driverId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNbPassagers(): ?int
    {
        return $this->nbPassagers;
    }

    public function setNbPassagers(int $nbPassagers): self
    {
        $this->nbPassagers = $nbPassagers;

        return $this;
    }

    public function getNbBagages(): ?int
    {
        return $this->nbBagages;
    }

    public function setNbBagages(?int $nbBagages): self
    {
        $this->nbBagages = $nbBagages;

        return $this;
    }

    public function getNumFlight(): ?string
    {
        return $this->numFlight;
    }

    public function setNumFlight(?string $numFlight): self
    {
        $this->numFlight = $numFlight;

        return $this;
    }

    public function getMessages(): ?string
    {
        return $this->messages;
    }

    public function setMessages(?string $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
