<?php

namespace App\Transformers;

use App\Chunk;
use League\Fractal\TransformerAbstract;

class ChunkTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Chunk $chunk)
    {
        return [
            'identifier'            => (int)$chunk->id,
            'transactionIdentifier' => (int)$chunk->transaction_id,
            'equipmentIdentifier'   => (int)$chunk->equipment_id,
            'tansactionType'        => ($chunk->status === 'true'), 
            'numOfPieces'           => (int)$chunk->quantity,
            'responsibility'        => $chunk->responsibility,
            'acquireDate'           => isset($chunk->first_use_date) ? (string)$chunk->first_use_date : null,
            'scatterDate'           => isset($chunk->last_use_date) ? (string)$chunk->last_use_date : null,
            'obtainedPiece'         => ($chunk->obtained === 'true'),
            'creationDate'          => (string)$chunk->created_at,
            'lastChange'            => (string)$chunk->updated_at,
            'deletedDate'           => isset($chunk->deleted_at) ? (string) $chunk->deleted_at : null,
        ];
    }
}
