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
}
