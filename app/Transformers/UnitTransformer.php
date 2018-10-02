<?php

namespace App\Transformers;

use App\Unit;
use League\Fractal\TransformerAbstract;

class UnitTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Unit $unit)
    {
        return [
            'identifier'   => (int)$unit->id,
            'title'        => (string)$unit->name,
            'cityName'     => (string)$unit->city,
            'streetName'   => (string)$unit->street,
            'streetNumber' => (int)$unit->street_number,
            'creationDate' => (string)$unit->created_at,
            'lastChange'   => (string)$unit->updated_at,
            'deletedDate'  => isset($unit->deleted_at) ? (string) $unit->deleted_at : null,
        ];
    }

     public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'   => 'id',
            'title'        => 'name',
            'cityName'     => 'city',
            'streetName'   => 'street',
            'streetNumber' => 'street_number',
            'creationDate' => 'created_at',
            'lastChange'   => 'updated_at',
            'deletedDate'  => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
