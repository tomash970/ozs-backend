<?php

namespace App\Http\Controllers\Transaction;

use App\Chunk;
use App\Http\Controllers\ApiController;
use App\Transaction;
use App\Transformers\ChunkTransformer;
use App\Transformers\TransactionTransformer;
use App\User;
use Illuminate\Http\Request;

class TransactionUserChunkController extends ApiController
{

    public function __construct()
    {
      parent::__construct();

      $this->middleware('transform.input:' . ChunkTransformer::class)->only(['store', 'update']);
      $this->middleware('scope:manage-transactions')->only(['store', 'update']);
      // $this->middleware('scope:verify-transactions, verify-transactions')->only(['update']);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transaction $transaction, Chunk $chunk, User $user)
    {
        $rules = [
            'equipment_id'    => 'required|integer',
            'status'          => 'in:' . Chunk::EXCEPTIONAL . ',' . Chunk::EXTRAORDINARY,
            'quantity'        => 'required|integer',
            'responsibility'  => 'required_if:status,' . Chunk::EXTRAORDINARY . '|
                                  in:' . Chunk::NO_RESPONSIBILITY . ',' . Chunk::PARTIAL_RESPONSIBILITY . ',' . Chunk::FULL_RESPONSIBILITY,
            'first_use_date'  => 'required_if:status,' . Chunk::EXTRAORDINARY . '|date',
            'last_use_date'   => 'required_if:status,' . Chunk::EXTRAORDINARY . '|date',
            'obtained'        => 'in:' . Chunk::OBTAINED  . ',' . Chunk::NOT_OBTAINED,
        ];

        $this->validate($request, $rules);

        if ($user->id != $transaction->user_id) {
           return $this->errorResponse('You have no valid role to perform action on this model!', 409);
        }

        if (!$user->isVerified()) {
            return $this->errorResponse('The user must be verified!', 409);
        }

        if ($user->roles->whereIn('name', 'boss')->isEmpty()) {
            return $this->errorResponse('The user role is not valid for this transaction!', 409);
        }

        

        $data = $request->all();
        //$data['responsibility'] = Chunk::NO_RESPONSIBILITY;
        $data['obtained']       = Chunk::NOT_OBTAINED;
        //$data['order_accepted'] = Transaction::NOT_ORDER_ACCEPTED;
        $data['transaction_id'] = $transaction->id;

        $newChunk = Chunk::create($data);
        return $this->showOne($newChunk, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction, User $user, Chunk $chunk)
    {
        $rules = [
            'equipment_id'    => 'integer',
            'status'          => 'in:' . Chunk::EXCEPTIONAL . ',' . Chunk::EXTRAORDINARY,
            'quantity'        => 'integer',
            'responsibility'  => 'required_if:status,' . Chunk::EXTRAORDINARY . '|
                                  in:' . Chunk::NO_RESPONSIBILITY . ',' . Chunk::PARTIAL_RESPONSIBILITY . ',' . Chunk::FULL_RESPONSIBILITY,
            'first_use_date'  => 'required_if:status,' . Chunk::EXTRAORDINARY . '|date',
            'last_use_date'   => 'required_if:status,' . Chunk::EXTRAORDINARY . '|date',
            'obtained'        => 'in:' . Chunk::OBTAINED  . ',' . Chunk::NOT_OBTAINED,
        ];

        $this->validate($request, $rules);

        if ($request->has('obtained')) {
            if (!$user->roles->contains('name', 'obtainer')) {
               return $this->errorResponse('You have no valid role (obtainer) to perform action on this model!', 409);
            }

            $chunk->fill($request->only(['obtained']));
            
        }

        if ($request->has('equipment_id') || $request->has('status') || $request->has('quantity') || $request->has('responsibility') || $request->has('first_use_date') || $request->has('last_use_date')) {
            if (!$user->roles->contains('name', 'boss')) {
               return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
            }
          
            $chunk->fill($request->except(['obtained'])); 

        }

        if ($chunk->isClean()) {
            return $this->errorResponse('You need to specify new value to update!', 422);
        }

        $chunk->save();
        return $this->showOne($chunk); 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction, User $user, Chunk $chunk)
    {
            if ($user->id != $transaction->user_id) {
               return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
            }
        $chunk->delete();

        return $this->showOne($chunk);
    }
    
}
