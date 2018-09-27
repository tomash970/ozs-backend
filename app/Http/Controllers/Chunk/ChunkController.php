<?php

namespace App\Http\Controllers\Chunk;

use App\Chunk;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class ChunkController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chunks = Chunk::all();

        return $this->showAll($chunks);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Chunk $chunk)
    {
        return $this->showOne($chunk);
    }

}
