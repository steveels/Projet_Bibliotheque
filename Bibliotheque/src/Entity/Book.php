<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_emprunt = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publish_year = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resume = null;

    #[ORM\Column]
    private ?bool $disponibility = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(type: "integer")]
    private ?int $etat_id = null;

    #[ORM\Column(type: "integer")]
    private ?int $categorie_id = null;




    /**
     * @var Collection<int, EmpruntLivre>
     */
    #[ORM\OneToMany(targetEntity: EmpruntLivre::class, mappedBy: 'book', orphanRemoval: true)]
    private Collection $empruntLivres;

    /**
     * @var Collection<int, Commentaires>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'book')]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Categories $categorie = null;

    /**
     * @var Collection<int, Reservation>
     * 
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="book")
     */
    private Collection $reservations;

    public function __construct()
    {
        $this->empruntLivres = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $date_emprunt): static
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishYear(): ?\DateTimeInterface
    {
        return $this->publish_year;
    }

    public function setPublishYear(?\DateTimeInterface $publish_year): static
    {
        $this->publish_year = $publish_year;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function isDisponibility(): ?bool
    {
        return $this->disponibility;
    }

    public function setDisponibility(bool $disponibility): static
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getEtatId(): ?int
{
    return $this->etat_id;
}

public function setEtatId(?int $etat_id): self
{
    $this->etat_id = $etat_id;
    return $this;
}

public function getCategorieId(): ?int
{
    return $this->categorie_id;
}

public function setCategorieId(?int $categorie_id): self
{
    $this->categorie_id = $categorie_id;
    return $this;
}
   

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $condition;

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): self
    {
        $this->condition = $condition;

        return $this;
    }




    /**
     * @return Collection<int, EmpruntLivre>
     */
    public function getEmpruntLivres(): Collection
    {
        return $this->empruntLivres;
    }

    public function addEmpruntLivre(EmpruntLivre $empruntLivre): static
    {
        if (!$this->empruntLivres->contains($empruntLivre)) {
            $this->empruntLivres->add($empruntLivre);
            $empruntLivre->setBook($this);
        }

        return $this;
    }

    public function removeEmpruntLivre(EmpruntLivre $empruntLivre): static
    {
        if ($this->empruntLivres->removeElement($empruntLivre)) {
            // set the owning side to null (unless already changed)
            if ($empruntLivre->getBook() === $this) {
                $empruntLivre->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setBook($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getBook() === $this) {
                $commentaire->setBook(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    
}
