<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmpruntLivreRepository;

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
    private ?\DateTimeInterface $dateRestitutionEffective = null;

    #[ORM\ManyToOne(inversedBy: 'empruntLivres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'empruntLivres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;
 /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $dateRestitutionPrevue = null;

   /**
 * @ORM\Column(type="datetime")
 */
private ?\DateTimeInterface $dateRetour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(DateTimeInterface $dateEmprunt): static
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

    public function getDateRestitutionEffective(): ?\DateTimeInterface
    {
        return $this->dateRestitutionEffective;
    }

    public function setDateRestitutionEffective(\DateTimeInterface $dateRestitutionEffective): static
    {
        $this->dateRestitutionEffective = $dateRestitutionEffective;

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

    public function getDateRestitutionPrevue(): ?\DateTimeInterface
    {
        return $this->dateRestitutionPrevue;
    }
    
    public function setDateRestitutionPrevue(\DateTimeInterface $dateRestitutionPrevue): self
    {
        $this->dateRestitutionPrevue = $dateRestitutionPrevue;
    
        return $this;
    }

    public function __toString(): string
{
    return (string) $this->getId();
}



}
