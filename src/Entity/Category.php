<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */

//on crée la class Category
class Category
{
    /**
     * on dédclare les annotations
     * @ORM\Id //On déclare l'ID de notre table
     * @ORM\GeneratedValue //On défini l'auto incrémentation
     * @ORM\Column(type="integer") //On défini son type : Integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * Je crée un lien entre les articles et les catégories
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */
    private $articles;

    public function __construct()
    {
        // permet de récupérer tous les articles en fonction d'une catégorie
        // dans un tableau afin d'éviter d'écraser un article déjà existant
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }
        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if(!$this->articles->removeElement($article)) {
            if($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getArticles(): ArrayCollection
    {
        return $this->articles;
    }

    /**
     * @param ArrayCollection $articles
     */
    public function setArticles(ArrayCollection $articles): void
    {
        $this->articles = $articles;
    }

}
