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

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('chunks.show', $chunk->id),
                ],
                [
                    'rel' => 'chunk.equipments',
                    'href' => route('chunks.equipments.index', $chunk->id),
                ],
                [
                    'rel' => 'chunk.transactions',
                    'href' => route('chunks.transactions.index', $chunk->id),
                ],
                [
                    'rel' => 'chunk.users',
                    'href' => route('chunks.users.index', $chunk->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'            => 'id',
            'transactionIdentifier' => 'transaction_id',
            'equipmentIdentifier'   => 'equipment_id',
            'tansactionType'        => 'status', 
            'numOfPieces'           => 'quantity',
            'responsibility'        => 'responsibility',
            'acquireDate'           => 'first_use_date',
            'scatterDate'           => 'last_use_date',
            'obtainedPiece'         => 'obtained',
            'creationDate'          => 'created_at',
            'lastChange'            => 'updated_at',
            'deletedDate'           => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'             => 'identifier',
            'transaction_id' => 'transactionIdentifier',
            'equipment_id'   => 'equipmentIdentifier',
            'status'         => 'tansactionType', 
            'quantity'       => 'numOfPieces',
            'responsibility' => 'responsibility',
            'first_use_date' => 'acquireDate',
            'last_use_date'  => 'scatterDate',
            'obtained'       => 'obtainedPiece',
            'created_at'     => 'creationDate',
            'updated_at'     => 'lastChange',
            'deleted_at'     => 'deletedDate',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
