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
        ];
    }
}
