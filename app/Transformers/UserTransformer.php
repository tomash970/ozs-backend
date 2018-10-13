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
            'email'              => (string)$user->email,
            'isVerified'         => (int)$user->verified,
            'isAdmin'            => ($user->admin === 'true'),
            'unitIdentifier'     => (int)$user->unit_id,
            'positionIdentifier' => (int)$user->position_id,
            'creationDate'       => (string)$user->created_at,
            'lastChange'         => (string)$user->updated_at,
            'deletedDate'        => isset($user->deleted_at) ? (string)$user->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id),
                ],
                [
                    'rel' => 'user.transactions',
                    'href' => route('users.transactions.index', $user->id),
                ],
                [
                    'rel' => 'user.chunks',
                    'href' => route('users.chunks.index', $user->id),
                ],
                [
                    'rel' => 'user.workplaces',
                    'href' => route('users.workplaces.index', $user->id),
                ],
                [
                    'rel' => 'user.roles',
                    'href' => route('users.roles.index', $user->id),
                ],
                [
                    'rel' => 'unit',
                    'href' => route('units.show', $user->unit_id),
                ],
                [
                    'rel' => 'position',
                    'href' => route('positions.show', $user->position_id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'            => 'id',
            'fullName'              => 'name',
            'firstName'             => 'first_name',
            'lastName'              => 'last_name',
            'email'                 => 'email',
            'password'              => 'password',
            'password_confirmation' => 'password_confirmation',
            'isVerified'            => 'verified',
            'isAdmin'               => 'admin',
            'unitIdentifier'        => 'unit_id',
            'positionIdentifier'    => 'position_id',
            'creationDate'          => 'created_at',
            'lastChange'            => 'updated_at',
            'deletedDate'           => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'                    => 'identifier',
            'name'                  => 'fullName',
            'first_name'            => 'firstName',
            'last_name'             => 'lastName',
            'email'                 => 'email',
            'password'              => 'password',
            'password_confirmation' => 'password_confirmation',
            'verified'              => 'isVerified',
            'admin'                 => 'isAdmin',
            'unit_id'               => 'unitIdentifier',
            'position_id'           => 'positionIdentifier',
            'created_at'            => 'creationDate',
            'updated_at'            => 'lastChange',
            'deleted_at'            => 'deletedDate',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
