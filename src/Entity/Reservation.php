<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $clientId = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $CreatAt = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $article = [];

    #[ORM\Column(nullable: true)]
    private ?bool $isTTC = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adressDepart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adressArrive = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPassager = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbBagage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $flight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remarque = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $time = null;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $operationAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $driver = null;

    #[ORM\Column(nullable: true)]
    private ?int $car = null;

    #[ORM\Column(nullable: true)]
    private ?bool $visible = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }


    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->CreatAt;
    }

    public function setCreatAt(\DateTimeImmutable $CreatAt): self
    {
        $this->CreatAt = $CreatAt;

        return $this;
    }



    public function getArticle(): array
    {
        return $this->article;
    }

    public function setArticle(?array $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function isIsTTC(): ?bool
    {
        return $this->isTTC;
    }

    public function setIsTTC(bool $isTTC): self
    {
        $this->isTTC = $isTTC;

        return $this;
    }

    public function getAdressDepart(): ?string
    {
        return $this->adressDepart;
    }

    public function setAdressDepart(string $adressDepart): self
    {
        $this->adressDepart = $adressDepart;

        return $this;
    }

    public function getAdressArrive(): ?string
    {
        return $this->adressArrive;
    }

    public function setAdressArrive(string $adressArrive): self
    {
        $this->adressArrive = $adressArrive;

        return $this;
    }

    public function getNbPassager(): ?int
    {
        return $this->nbPassager;
    }

    public function setNbPassager(int $nbPassager): self
    {
        $this->nbPassager = $nbPassager;

        return $this;
    }

    public function getNbBagage(): ?int
    {
        return $this->nbBagage;
    }

    public function setNbBagage(int $nbBagage): self
    {
        $this->nbBagage = $nbBagage;

        return $this;
    }

    public function getFlight(): ?string
    {
        return $this->flight;
    }

    public function setFlight(?string $flight): self
    {
        $this->flight = $flight;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

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


    public function getOperationAt(): ?\DateTimeInterface
    {
        return $this->operationAt;
    }

    public function setOperationAt(\DateTimeInterface $operationAt): self
    {
        $this->operationAt = $operationAt;

        return $this;
    }

    public function getDriver(): ?int
    {
        return $this->driver;
    }

    public function setDriver(int $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getCar(): ?int
    {
        return $this->car;
    }

    public function setCar(int $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}
