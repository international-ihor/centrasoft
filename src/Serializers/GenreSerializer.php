<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class GenreSerializer extends AbstractSerializer
{
    protected $type = 'genres';

    public function getAttributes($genre, array $fields = null)
    {
        return [
            'name' => $genre->name
        ];
    }
}