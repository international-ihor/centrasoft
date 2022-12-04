<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{
    protected $type = 'users';

    public function getAttributes($user, array $fields = null)
    {
        return [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'address'  => $user->address,
            'email' => $user->email
        ];
    }
}