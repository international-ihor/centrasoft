<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
#[ORM\Table(name: 'record')]
class Record
{
    #[ORM\Id] 
    #[ORM\Column(type: "integer")] 
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int|null $id = null;

    #[ORM\Column(name: '`user_id`', type: 'string')]
    private string $user_id;

    #[ORM\Column(name: '`book_id`', type: 'string')]
    private string $book_id;

    #[ORM\Column(name: '`issue_date`', type: 'datetime')]
    private \DateTime $issue_date;

    #[ORM\Column(name: '`return_date`', type: 'datetime')]
    private \DateTime|null $return_date;

    #[ORM\PrePersist, ORM\PreUpdate]
    public function validate()
    {
        if ( empty($this->user_id) ) {
            throw new ORM\ValidateException('User Id is empty');
        }
        if ( empty($this->book_id) ) {
            throw new ORM\ValidateException('Book Id is empty');
        }
        if ( empty($this->issue_date) ) {
            throw new ORM\ValidateException('Issue date is empty');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getBookId(): int
    {
        return $this->book_id;
    }

    public function setBookId(string $book_id): void
    {
        $this->book_id = $book_id;
    }

    public function getIssueDate(): \DateTime
    {
        return $this->issue_date;
    }

    public function setIssueDate(string $issue_date): void
    {
        $this->issue_date = $issue_date;
    }

    public function getReturnDate(): \DateTime
    {
        return $this->return_date;
    }

    public function setReturnDate(string $return_date): void
    {
        $this->return_date = $return_date;
    }


}