<?php

namespace App\Transformers;

use App\Equipment;
use League\Fractal\TransformerAbstract;

class EquipmentTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Equipment $equipment)
    {
        return [
            'identifier'        => (int)$equipment->id,
            'equipmentName'     => (string)$equipment->name,
            'equipmentCode'     => (int)$equipment->specific_number,
            'equipmentSizes'    => $equipment->size_json, 
            'equipmentDocument' => url("img/{$equipment->rules_paper}"),
            'creationDate'      => (string)$equipment->created_at,
            'lastChange'        => (string)$equipment->updated_at,
            'deletedDate'       => isset($equipment->deleted_at) ? (string) $equipment->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('equipments.show', $equipment->id),
                ],
                [
                    'rel' => 'equipment.transactions',
                    'href' => route('equipments.transactions.index', $equipment->id),
                ],
                [
                    'rel' => 'equipment.workplaces',
                    'href' => route('equipments.workplaces.index', $equipment->id),
                ],
            ]
        ];
    }

     public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'        => 'id',
            'equipmentName'     => 'name',
            'equipmentCode'     => 'specific_number',
            'equipmentSizes'    => 'size_json', 
            'equipmentDocument' => 'rules_paper',
            'creationDate'      => 'created_at',
            'lastChange'        => 'updated_at',
            'deletedDate'       => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
