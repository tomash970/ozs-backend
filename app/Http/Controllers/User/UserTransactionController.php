<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Transaction;
use App\Transformers\TransactionTransformer;
use App\User;
use Illuminate\Http\Request;

class UserTransactionController extends ApiController
{

    public function __construct()
    {
      parent::__construct();

      $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $transactions = $user->transactions;

        return $this->showAll($transactions);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {

        $rules = [
            //'worker_name'       => 'required|regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:60',
            'worker_first_name' => 'required|regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30', 
            'worker_last_name'  => 'required|regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30',   
            'workplace_id'      => 'required|integer',
        ];

        if (!$user->isVerified()) {
            return $this->errorResponse('The user must be a verified!', 409);
        }

        if ($user->roles->whereIn('name', 'boss')->isEmpty()) {
            return $this->errorResponse('The user role is not valid for this transaction!', 409);
        }

        $this->validate($request, $rules);

        $data = $request->all();
        $data['worker_name']    = $request->worker_first_name . ' ' . $request->worker_last_name;
        $data['confirmation']   = Transaction::NOT_CONFIRMED;
        $data['order_accepted'] = Transaction::NOT_ORDER_ACCEPTED;
        $data['user_id']        = $user->id;

        $newTransaction = Transaction::create($data);
        return $this->showOne($newTransaction, 201);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Transaction $transaction)
    {
        if ($user->id != $transaction->user_id) {
           return $this->errorResponse('You have no valid role to perform action on this model!', 409);
        }
        return $this->showOne($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Transaction $transaction)
    {   

        $rules = [
            'worker_frist_name' => 'regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30', 
            'worker_last_name'  => 'regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30',   
            'confirmation'      => 'boolean',
            'order_accepted'    => 'boolean',
            'workplace_id'      => 'integer',
        ];

        $this->validate($request, $rules);



        if ($request->has('worker_first_name') || $request->has('worker_last_name') || $request->has('workplace_id')) {
            if (!$user->roles->contains('name', 'boss')) {
               return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
            }

            if ($user->id != $transaction->user_id) {
               return $this->errorResponse('Only user with credentials for this worker can perform action on this model!', 409);
            }

            if ($request->has('workplace_id')) {
                $transaction->workplace_id = $request->workplace_id;
            }

            if(!$request->has('worker_first_name') && $request->has('worker_last_name')){
                $transaction->worker_name       = $transaction->worker_first_name . ' ' . $request->worker_last_name;
                $transaction->worker_last_name  = $request->worker_last_name;
            }

            if($request->has('worker_first_name') && !$request->has('worker_last_name')){
                $transaction->worker_name = $request->worker_first_name . ' ' . $transaction->worker_last_name;
                $transaction->worker_first_name = $request->worker_first_name;
            }

            if($request->has('worker_first_name') && $request->has('worker_last_name')){
                $transaction->worker_name = $request->worker_first_name . ' ' . $request->worker_last_name;
                $transaction->worker_first_name = $request->worker_first_name;
                $transaction->worker_last_name  = $request->worker_last_name;
            }




            // $transactions->worker_name = $request->worker_worker_first_name . ' ' . $request->worker_worker_last_name;
            // $transactions->worker_worker_first_name = $request->worker_worker_first_name;
            // $transactions->worker_worker_last_name  = $request->worker_worker_last_name;
            //$transaction->fill($request->only(['worker_name', 'workplace_id'])); 

        }

        // if ($request->has('workplace_id')) {
        //     if (!$user->roles->contains('name', 'boss')) {
        //        return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
        //     }
        //     $transaction->fill($request->only(['workplace_id'])); 

        // }

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
    public function destroy(User $user, Transaction $transaction)
    {
        if ($user->id != $transaction->user_id) {
               return $this->errorResponse('You have no valid role (boss) to perform action on this model!', 409);
            }
        $transaction->delete();

        return $this->showOne($transaction);
    }

}
