<?php

namespace App\Http\Controllers\Chunk;

use App\Chunk;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ChunkTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Chunk $chunk)
    {
        $transaction = $chunk->transaction;
        return $this->showOne($transaction);
    }

   
}
