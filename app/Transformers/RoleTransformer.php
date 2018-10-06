<?php

namespace App\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'identifier'   => (int)$role->id,
            'title'        => (string)$role->name,
            'creationDate' => (string)$role->created_at,
            'lastChange'   => (string)$role->updated_at,
            'deletedDate'  => isset($role->deleted_at) ? (string) $role->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('roles.show', $role->id),
                ],
                [
                    'rel' => 'role.positions',
                    'href' => route('roles.positions.index', $role->id),
                ],
                [
                    'rel' => 'role.units',
                    'href' => route('roles.units.index', $role->id),
                ],
                [
                    'rel' => 'role.users',
                    'href' => route('roles.users.index', $role->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'   => 'id',
            'title'        => 'name',
            'creationDate' => 'created_at',
            'lastChange'   => 'updated_at',
            'deletedDate'  => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'         => 'identifier',
            'name'       => 'title',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
