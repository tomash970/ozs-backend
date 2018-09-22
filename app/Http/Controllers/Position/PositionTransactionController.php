<?php

namespace App\Http\Controllers\Position;

use App\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PositionTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Position $position)
    {
        $transactions = $position->users()->whereHas('transactions')->with('transactions')->get()->pluck('transactions')->collapse();
        return $this->showAll($transactions);
    }
}
