<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class GenreSerializer extends AbstractSerializer
{
    protected $type = 'genre';

    public function getAttributes($genre, array $fields = null)
    {
        return [
            'id' => $genre->getId(),
            'name' => $genre->getName()
        ];
    }
}