<?php

namespace App\Http\Controllers\Unit;

use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UnitChunkController extends ApiController
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
        $chunks = $unit->users()->with('transactions.chunks')->get()->pluck('transactions')->collapse()->pluck('chunks')->collapse();
        return $this->showAll($chunks);
    }

}
