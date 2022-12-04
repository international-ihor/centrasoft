<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{
    protected $type = 'user';

    public function getAttributes($user, array $fields = null)
    {
        return [
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'address'  => $user->getAddress(),
            'email' => $user->getEmail()
        ];
    }
}