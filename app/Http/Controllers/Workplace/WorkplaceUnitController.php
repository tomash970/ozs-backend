<?php

namespace App\Http\Controllers\Workplace;

use App\Workplace;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class WorkplaceUnitController extends ApiController
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
    public function index(Workplace $workplace)
    {
        $units = $workplace->transactions()->whereHas('user')->with('user.unit')->get()->pluck('user')->pluck('unit')->unique('id')->values();
        return $this->showAll($units);
    }
}
