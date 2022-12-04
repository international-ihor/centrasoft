<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: '`id`', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int|null $id = null;

    #[ORM\Column(name: '`first_name`', type: 'string')]
    public string|null $first_name = null;

    #[ORM\Column(name: '`last_name`', type: 'string')]
    public string|null $last_name = null;

    #[ORM\Column(name: '`address`', type: 'string')]
    public string|null $address = null;

    #[ORM\Column(name: '`email`', type: 'string')]
    public string|null $email = null;

    #[ORM\PrePersist, ORM\PreUpdate]
    public function validate()
    {
        if ( empty($this->first_name) ) {
            throw new ORM\ValidateException('First name is empty');
        }
        if ( empty($this->last_name) ) {
            throw new ORM\ValidateException('Last name is empty');
        }
        if ( empty($this->address) ) {
            throw new ORM\ValidateException('Address is empty');
        }
        if ( empty($this->email) ) {
            throw new ORM\ValidateException('Email is empty');
        }
        if ( filter_var($this->email, FILTER_VALIDATE_EMAIL) ) {
            throw new ORM\ValidateException('Email is invalid');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string|null
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string|null
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getAddress(): string|null
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}