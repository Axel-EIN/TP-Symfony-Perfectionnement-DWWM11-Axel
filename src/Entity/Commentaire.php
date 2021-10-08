<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="date")
     */
    private $datePublication;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    public function getId(): ?int {
        return $this->id;
    }

    public function getContenu(): ?string {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): self {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getAuteur(): ?Utilisateur {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): self {
        $this->auteur = $auteur;

        return $this;
    }

    public function getArticle(): ?Article {
        return $this->article;
    }

    public function setArticle(?Article $article): self {
        $this->article = $article;

        return $this;
    }

    public function getNote(): ?int {
        return $this->note;
    }

    public function setNote(int $note): self {
        $this->note = $note;

        return $this;
    }

    public function upVote() {
        $this->note++;
        return $this;
    }
    
    public function downVote() {
        $this->note--;
        return $this;
    }
}
