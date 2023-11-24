<?php

namespace App\Entity;

use App\Repository\FactureLibreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureLibreRepository::class)]
class FactureLibre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $companyId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $article1 = null;

    #[ORM\Column]
    private ?float $price1 = null;

    #[ORM\Column]
    private ?int $quantite1 = null;

    #[ORM\Column]
    private ?float $tva1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $article2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $price2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $article3 = null;

    #[ORM\Column(nullable: true)]
    private ?float $price3 = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite3 = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $article4 = null;

    #[ORM\Column(nullable: true)]
    private ?float $price4 = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite4 = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva4 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    ////////////////////////////////////////////////////
    public function getArticle1(): ?string
    {
        return $this->article1;
    }

    public function setArticle1(string $article1): self
    {
        $this->article1 = $article1;

        return $this;
    }

    public function getPrice1(): ?float
    {
        return $this->price1;
    }

    public function setPrice1(float $price1): self
    {
        $this->price1 = $price1;

        return $this;
    }

    public function getQuantite1(): ?int
    {
        return $this->quantite1;
    }

    public function setQuantite1(int $quantite1): self
    {
        $this->quantite1 = $quantite1;

        return $this;
    }

    public function getTva1(): ?float
    {
        return $this->tva1;
    }

    public function setTva1(float $tva1): self
    {
        $this->tva1 = $tva1;

        return $this;
    }

    ////////////////////////////////////////////////////
    public function getArticle2(): ?string
    {
        return $this->article2;
    }

    public function setArticle2(string $article2): self
    {
        $this->article2 = $article2;

        return $this;
    }

    public function getPrice2(): ?float
    {
        return $this->price2;
    }

    public function setPrice2(float $price2): self
    {
        $this->price2 = $price2;

        return $this;
    }

    public function getQuantite2(): ?int
    {
        return $this->quantite2;
    }

    public function setQuantite2(int $quantite2): self
    {
        $this->quantite2 = $quantite2;

        return $this;
    }

    public function getTva2(): ?float
    {
        return $this->tva2;
    }

    public function setTva2(float $tva2): self
    {
        $this->tva2 = $tva2;

        return $this;
    }
    ////////////////////////////////////////////////////
    public function getArticle3(): ?string
    {
        return $this->article3;
    }

    public function setArticle3(string $article3): self
    {
        $this->article3 = $article3;

        return $this;
    }

    public function getPrice3(): ?float
    {
        return $this->price3;
    }

    public function setPrice3(float $price3): self
    {
        $this->price3 = $price3;

        return $this;
    }

    public function getQuantite3(): ?int
    {
        return $this->quantite3;
    }

    public function setQuantite3(int $quantite3): self
    {
        $this->quantite3 = $quantite3;

        return $this;
    }

    public function getTva3(): ?float
    {
        return $this->tva3;
    }

    public function setTva3(float $tva3): self
    {
        $this->tva3 = $tva3;

        return $this;
    }
    ////////////////////////////////////////////////////
    public function getArticle4(): ?string
    {
        return $this->article4;
    }

    public function setArticle4(string $article4): self
    {
        $this->article4 = $article4;

        return $this;
    }

    public function getPrice4(): ?float
    {
        return $this->price4;
    }

    public function setPrice4(float $price4): self
    {
        $this->price4 = $price4;

        return $this;
    }

    public function getQuantite4(): ?int
    {
        return $this->quantite4;
    }

    public function setQuantite4(int $quantite4): self
    {
        $this->quantite4 = $quantite4;

        return $this;
    }

    public function getTva4(): ?float
    {
        return $this->tva4;
    }

    public function setTva4(float $tva4): self
    {
        $this->tva4 = $tva4;

        return $this;
    }
}
