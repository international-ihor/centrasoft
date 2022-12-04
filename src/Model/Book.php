<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'book')]
class Book
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int|null $id = null;

    #[ORM\Column(name: '`title`', type: 'string')]
    private string $title;

    #[ORM\Column(name: '`author`', type: 'string')]
    private string $author;

    #[ORM\PrePersist, ORM\PreUpdate]
    public function validate()
    {
        if ( empty($this->title) ) {
            throw new ORM\ValidateException('Title is empty');
        }
        if ( empty($this->author) ) {
            throw new ORM\ValidateException('Author is empty');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->title = $author;
    }
}