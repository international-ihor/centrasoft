<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Table(name: 'genre')]
class Genre
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int|null $id = null;

    #[ORM\Column(name: '`name`', type: 'string')]
    private string $name;

    #[ORM\PrePersist, ORM\PreUpdate]
    public function validate()
    {
        if ( empty($this->name) ) {
            throw new ORM\ValidateException('Name is empty');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setFirstName(string $name): void
    {
        $this->name = $name;
    }
}