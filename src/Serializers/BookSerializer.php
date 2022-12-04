<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class BookSerializer extends AbstractSerializer
{
    protected $type = 'book';

    public function getAttributes($book, array $fields = null)
    {
        return [
            'id' => $book->getId(),
            'author' => $book->getAuthor(),
            'title'  => $book->getTitle()
        ];
    }
}