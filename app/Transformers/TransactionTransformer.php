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
        ];
    }
}
