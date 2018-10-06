<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier'          => (int)$transaction->id,
            'workerFullName'      => (string)$transaction->worker_name,
            'workerFirstName'     => (string)$transaction->worker_first_name,
            'workerLastName'      => (string)$transaction->worker_last_name,
            'userIdentifier'      => (int)$transaction->user_id,
            'transactionCheck'    => ($transaction->confirmation === 'true'),
            'transactionAccepted' => ($transaction->order_accepted === 'true'),
            'workplaceIdentifier' => (int)$transaction->workplace_id,
            'creationDate'        => (string)$transaction->created_at,
            'lastChange'          => (string)$transaction->updated_at,
            'deletedDate'         => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel' => 'transaction.equipments',
                    'href' => route('transactions.equipments.index', $transaction->id),
                ],
                [
                    'rel' => 'transaction.chunks',
                    'href' => route('transactions.chunks.index', $transaction->id),
                ],
                [
                    'rel' => 'transaction.units',
                    'href' => route('transactions.units.index', $transaction->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $transaction->user_id),
                ],
                [
                    'rel' => 'workplace',
                    'href' => route('workplaces.show', $transaction->workplace_id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'          => 'id',
            'fullName'            => 'name',
            'firstName'           => 'first_name',
            'lastName'            => 'last_name',
            'userIdentifier'      => 'user_id',
            'transactionCheck'    => 'confirmation',
            'transactionAccepted' => 'order_accepted',
            'workplaceIdentifier' => 'workplace_id',
            'creationDate'        => 'created_at',
            'lastChange'          => 'updated_at',
            'deletedDate'         => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'             => 'identifier',
            'name'           => 'fullName',
            'first_name'     => 'firstName',
            'last_name'      => 'lastName',
            'user_id'        => 'userIdentifier',
            'confirmation'   => 'transactionCheck',
            'order_accepted' => 'transactionAccepted',
            'workplace_id'   => 'workplaceIdentifier',
            'created_at'     => 'creationDate',
            'updated_at'     => 'lastChange',
            'deleted_at'     => 'deletedDate',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
