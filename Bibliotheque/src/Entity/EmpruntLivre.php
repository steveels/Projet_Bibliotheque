<?php

namespace App\Entity;

use App\Repository\EmpruntLivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntLivreRepository::class)]
class EmpruntLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEmprunt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRestitution = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRestituionEffective = null;

    #[ORM\ManyToOne(inversedBy: 'empruntLivres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'empruntLivres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;
 /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetour;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateRestitution(): ?\DateTimeInterface
    {
        return $this->dateRestitution;
    }

    public function setDateRestitution(\DateTimeInterface $dateRestitution): static
    {
        $this->dateRestitution = $dateRestitution;

        return $this;
    }

    public function getDateRestituionEffective(): ?\DateTimeInterface
    {
        return $this->dateRestituionEffective;
    }

    public function setDateRestituionEffective(\DateTimeInterface $dateRestituionEffective): static
    {
        $this->dateRestituionEffective = $dateRestituionEffective;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }
    
    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }
}
