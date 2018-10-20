<?php

namespace App\Http\Controllers\Unit;

use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UnitTransactionController extends ApiController
{
  public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Unit $unit)
    {
        $transactions =  $unit->users()
              ->whereHas('transactions')
              ->with('transactions')
              ->get()
              ->pluck('transactions')
              ->collapse();

              return $this->showAll($transactions);
    }

}
