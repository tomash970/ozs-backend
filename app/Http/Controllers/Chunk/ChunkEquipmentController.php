<?php

namespace App\Http\Controllers\Chunk;

use App\Chunk;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ChunkEquipmentController extends ApiController
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
    public function index(Chunk $chunk)
    {
        $equipment = $chunk->equipment;
        return $this->showOne($equipment);
    }
}
