<?php

namespace App\Http\Controllers\Workplace;

use App\Http\Controllers\ApiController;
use App\Workplace;
use Illuminate\Http\Request;

class WorkplaceTransactionController extends ApiController
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
    public function index(Workplace $workplace)
    {
        $transactions = $workplace->transactions;
        return $this->showAll($transactions);
    }

  
}
