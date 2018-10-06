<?php

namespace App\Transformers;

use App\Workplace;
use League\Fractal\TransformerAbstract;

class WorkplaceTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Workplace $workplace)
    {
        return [
            'identifier'    => (int)$workplace->id,
            'title'         => (string)$workplace->name,
            'workplaceCode' => (int)$workplace->specific_number,
            'creationDate'  => (string)$workplace->created_at,
            'lastChange'    => (string)$workplace->updated_at,
            'deletedDate'   => isset($workplace->deleted_at) ? (string) $workplace->deleted_at : null,
            
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('workplaces.show', $workplace->id),
                ],
                [
                    'rel' => 'workplace.equipments',
                    'href' => route('workplaces.equipments.index', $workplace->id),
                ],
                [
                    'rel' => 'workplace.transactions',
                    'href' => route('workplaces.transactions.index', $workplace->id),
                ],
                [
                    'rel' => 'workplace.units',
                    'href' => route('workplaces.units.index', $workplace->id),
                ],
                [
                    'rel' => 'workplace.users',
                    'href' => route('workplaces.users.index', $workplace->id),
                ],
            ]
        ];

    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'title'         => 'name',
            'workplaceCode' => 'specific_number',
            'creationDate'  => 'created_at',
            'lastChange'    => 'updated_at',
            'deletedDate'   => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'              => 'identifier',
            'name'            => 'title',
            'specific_number' => 'workplaceCode',
            'created_at'      => 'creationDate',
            'updated_at'      => 'lastChange',
            'deleted_at'      => 'deletedDate',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
