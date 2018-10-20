<?php

namespace App\Http\Controllers\Equipment;

use App\Equipment;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class EquipmentTransactionController extends ApiController
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
    public function index(Equipment $equipment)
    {
        $transactions = $equipment->chunks()
                ->with('transaction')
                ->get()
                ->pluck('transaction');
        //$transactions = $equipment->chunks()->with('transaction')->get()->pluck('transaction')->unique('id')->values();
                
//dd($transactions);
        return $this->showAll($transactions);
    }

   
}
