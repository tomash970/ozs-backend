<?php

namespace App\Http\Controllers\Position;

use App\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PositionUserController extends ApiController
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
    public function index(Position $position)
    {
        $users = $position->users;
        return $this->showAll($users);
    }

}
