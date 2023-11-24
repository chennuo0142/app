<?php

namespace App\Entity;

use App\Repository\UserSettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSettingRepository::class)]
class UserSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'userSetting', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $article = null;

    #[ORM\Column]
    private ?bool $bank = null;

    #[ORM\Column]
    private ?bool $car = null;

    #[ORM\Column]
    private ?bool $driver = null;

    #[ORM\Column]
    private ?bool $client = null;

    #[ORM\Column]
    private ?bool $company = null;

    #[ORM\Column]
    private ?bool $showBank = null;

    #[ORM\Column]
    private ?bool $isReservation = null;

    #[ORM\Column]
    private ?bool $tva = true;

    #[ORM\Column]
    private ?bool $noTvaText = false;

    #[ORM\Column(length: 255)]
    private ?string $textLawTva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isArticle(): ?bool
    {
        return $this->article;
    }

    public function setArticle(bool $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function isBank(): ?bool
    {
        return $this->bank;
    }

    public function setBank(bool $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function isCar(): ?bool
    {
        return $this->car;
    }

    public function setCar(bool $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function isDriver(): ?bool
    {
        return $this->driver;
    }

    public function setDriver(bool $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function isClient(): ?bool
    {
        return $this->client;
    }

    public function setClient(bool $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function isCompany(): ?bool
    {
        return $this->company;
    }

    public function setCompany(bool $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function isShowBank(): ?bool
    {
        return $this->showBank;
    }

    public function setShowBank(bool $showBank): self
    {
        $this->showBank = $showBank;

        return $this;
    }

    public function isIsReservation(): ?bool
    {
        return $this->isReservation;
    }

    public function setIsReservation(bool $isReservation): self
    {
        $this->isReservation = $isReservation;

        return $this;
    }

    public function isTva(): ?bool
    {
        return $this->tva;
    }

    public function setTva(bool $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function isNoTvaText(): ?bool
    {
        return $this->noTvaText;
    }

    public function setNoTvaText(bool $noTvaText): self
    {
        $this->noTvaText = $noTvaText;

        return $this;
    }

    public function getTextLawTva(): ?string
    {
        return $this->textLawTva;
    }

    public function setTextLawTva(string $textLawTva): self
    {

        $this->textLawTva = $textLawTva;

        return $this;
    }
}
