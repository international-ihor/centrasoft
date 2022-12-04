<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{
    protected $type = 'genres';

    public function getAttributes($genre, array $fields = null)
    {
        return [
            'name' => $genre->name
        ];
    }
}