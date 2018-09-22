<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionEquipmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $equipments = $transaction->chunks()->with('equipment')->get()->pluck('equipment')->unique('id')->values();

        return $this->showAll($equipments);
    }

}
