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
        ];
    }
}
