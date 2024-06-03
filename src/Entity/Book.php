<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $authorName = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Shelf", inversedBy: "books")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Shelf $shelf = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(?string $authorName): static
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getShelf(): ?Shelf
    {
        return $this->shelf;
    }

    public function setShelf(?Shelf $shelf): static
    {
        $this->shelf = $shelf;

        return $this;
    }
}