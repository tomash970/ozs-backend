<?php

namespace App\Http\Controllers\Unit;

use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UnitPositionController extends ApiController
{

    public function __construct()
    {
      $this->middleware('client.credentials')->only(['index']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Unit $unit)
    {
        $positions = $unit->users()->with('position')->get()->pluck('position')->unique()->values();
        return $this->showAll($positions);
    }

}
