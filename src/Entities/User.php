<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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
        if ( empty($this->first_name) < time()) {
            throw new ValidateException();
        }
        if ( empty($this->last_name) < time()) {
            throw new ValidateException();
        }
        if ( empty($this->address) < time()) {
            throw new ValidateException();
        }
        if ( empty($this->email) < time()) {
            throw new ValidateException();
        }
    }
}