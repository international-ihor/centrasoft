<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'record')]
class Record
{
    #[ORM\Id] 
    #[ORM\Column(type: "integer")] 
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id = null;
}