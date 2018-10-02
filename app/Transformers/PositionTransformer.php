<?php

namespace App\Transformers;

use App\Position;
use League\Fractal\TransformerAbstract;

class PositionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Position $position)
    {
        return [
            'identifier'   => (int)$position->id,
            'title'        => (string)$position->name,
            'creationDate' => (string)$position->created_at,
            'lastChange'   => (string)$position->updated_at,
            'deletedDate'  => isset($position->deleted_at) ? (string) $position->deleted_at : null,
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
