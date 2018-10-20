<?php

namespace App\Http\Controllers\Role;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class RoleUnitController extends ApiController
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
    public function index(Role $role)
    {
        $unit = $role->users()->with('unit')->get()->pluck('unit')->unique()->values();
        return $this->showAll($unit);
    }
}
