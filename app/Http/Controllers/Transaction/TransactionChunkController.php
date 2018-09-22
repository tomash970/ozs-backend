<?php

namespace App\Http\Controllers\Transaction;

use App\Chunk;
use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionChunkController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $chunks = $transaction->chunks;
        return $this->showAll($chunks);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transaction $transaction)
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

        // if (!$user->isVerified()) {
        //     return $this->errorResponse('The user must be verified!', 409);
        // }

        // if ($user->roles->whereIn('name', 'boss')->isEmpty()) {
        //     return $this->errorResponse('The user role is not valid for this transaction!', 409);
        // }

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Chunk $chunk, Transaction $transaction)
    {
        if ($user->id != $transaction->user_id) {
           return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
        }
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chunk $chunk, Transaction $transaction)
    {   

        $rules = [
            'worker_name'    => 'alpha_num|min:2|max:50',  
            'confirmation'   => 'boolean',
            'order_accepted' => 'boolean',
            'workplace_id'   => 'integer',
        ];

        $this->validate($request, $rules);

        if (($request->has('worker_name') || $request->has('workplace_id'))) {
            if (!$user->roles->contains('name', 'boss')) {
               return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
            }
          
            $transaction->fill($request->only(['worker_name', 'workplace_id'])); 

        }

        if ($request->has('order_accepted')) {
            if (!$user->roles->contains('name', 'obtainer')) {
               return $this->errorResponse('You have no valid role (obtainer) to perform action on this model!', 409);
            }

            $transaction->fill($request->only(['order_accepted']));
            
        } 

        if ($request->has('confirmation')) {
            if (!$user->roles->contains('name', 'manager')) {
               return $this->errorResponse('You have no valid role (manager) to perform action on this model!', 409);
            }

            $transaction->fill($request->only(['confirmation']));

        }

        if (!$user->roles->contains('name', 'boss') && !$user->roles->contains('name', 'manager') && !$user->roles->contains('name', 'obtainer')) {

            return $this->errorResponse('You have no valid role to perform any action on  any of the models!', 409);

        }

        if ($transaction->isClean()) {
            return $this->errorResponse('You need to specify new value to update!', 422);
        }

        $transaction->save();
        return $this->showOne($transaction);
    }

       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chunk $chunk, Transaction $transaction)
    {
        if ($user->id != $transaction->user_id) {
               return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
            }
        $transaction->delete();

        return $this->showOne($transaction);
    }
}
