<?php

namespace App\Http\Controllers\Transaction;

use App\Chunk;
use App\Http\Controllers\ApiController;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class TransactionUserChunkController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
  
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transaction $transaction, Chunk $chunk, User $user)
    {

        // 'transaction_id',
        // 'equipment_id',
        // 'status',
        // 'quantity',
        // 'responsibility',
        // 'first_use_date',
        // 'last_use_date',
        // 'obtained',
        $rules = [
            //'transaction_id'  => 'required|integer',
            'equipment_id'    => 'required|integer',
            'status'          => 'in:' . Chunk::EXCEPTIONAL . ',' . Chunk::EXTRAORDINARY,
            'quantity'        => 'required|integer',
            'responsibility'  => 'required_if:status,' . Chunk::EXTRAORDINARY . '|
                                  in:' . Chunk::NO_RESPONSIBILITY . ',' . Chunk::PARTIAL_RESPONSIBILITY . ',' . Chunk::FULL_RESPONSIBILITY,
            'first_use_date'  => 'required_if:status,' . Chunk::EXTRAORDINARY . '|date',
            'last_use_date'   => 'required_if:status,' . Chunk::EXTRAORDINARY . '|date',
            'obtained'        => 'in:' . Chunk::OBTAINED  . ',' . Chunk::NOT_OBTAINED,
        ];

        if ($user->id != $transaction->user_id) {
           return $this->errorResponse('You have no valid role to perform action on this model!', 409);
        }

        if (!$user->isVerified()) {
            return $this->errorResponse('The user must be verified!', 409);
        }

        if ($user->roles->whereIn('name', 'boss')->isEmpty()) {
            return $this->errorResponse('The user role is not valid for this transaction!', 409);
        }

        $this->validate($request, $rules);

        $data = $request->all();
        //$data['responsibility'] = Chunk::NO_RESPONSIBILITY;
        $data['obtained']       = Chunk::NOT_OBTAINED;
        //$data['order_accepted'] = Transaction::NOT_ORDER_ACCEPTED;
        $data['transaction_id'] = $transaction->id;

        $newChunk = Chunk::create($data);
        return $this->showOne($newChunk, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
    
}
