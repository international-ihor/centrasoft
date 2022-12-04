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
    private $id = null;

    #[Column(name: '`first_name`', type: 'string')]
    private string $first_name;

    #[Column(name: '`last_name`', type: 'string')]
    private string $last_name;

    #[Column(name: '`address`', type: 'string')]
    private string $address;

    #[Column(name: '`email`', type: 'string')]
    private string $email;

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
        if ( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            throw new ORM\ValidateException('Email is invalid');
        }
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getAddress(): string
    {
        return $this->last_name;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}