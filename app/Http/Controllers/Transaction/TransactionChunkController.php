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

}
