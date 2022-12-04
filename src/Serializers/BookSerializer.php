<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class BookSerializer extends AbstractSerializer
{
    protected $type = 'books';

    public function getAttributes($book, array $fields = null)
    {
        return [
            'author' => $book->author,
            'title'  => $book->title
        ];
    }
}