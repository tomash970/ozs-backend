<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier'         => (int)$user->id,
            'fullName'           => (string)$user->name,
            'firstName'          => (string)$user->first_name,
            'lastName'           => (string)$user->last_name,
            'isVerified'         => (int)$user->verified,
            'isAdmin'            => ($user->admin === 'true'),
            'unitIdentifier'     => (int)$user->unit_id,
            'positionIdentifier' => (int)$user->position_id,
            'creationDate'       => (string)$user->created_at,
            'lastChange'         => (string)$user->updated_at,
            'deletedDate'        => isset($user->deleted_at) ? (string) $users->deleted_at : null,

        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'         => 'id',
            'fullName'           => 'name',
            'firstName'          => 'first_name',
            'lastName'           => 'last_name',
            'isVerified'         => 'verified',
            'isAdmin'            => 'admin',
            'unitIdentifier'     => 'unit_id',
            'positionIdentifier' => 'position_id',
            'creationDate'       => 'created_at',
            'lastChange'         => 'updated_at',
            'deletedDate'        => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
