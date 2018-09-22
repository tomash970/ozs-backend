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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
        'transaction_id' => 'required|integer',
        'equipment_id'   => 'required|integer',
        'status'         => 'required|alpha_num|min:2|max:50',
        'quantity'       => 'required|alpha_num|min:2|max:50',
        'responsibility' => 'required|alpha_num|min:2|max:50',
        'first_use_date' => 'required|alpha_num|min:2|max:50',
        'last_use_date'  => 'required|alpha_num|min:2|max:50',
        'obtained'       => 'required|alpha_num|min:2|max:50',
        ];

        $this->validate($request, $rules);

        $newChunk = Chunk::create($request->all());
        return $this->showOne($newChunk,201);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chunk $chunk)
    {

                

        $rules = [
            'transaction_id' => 'required|integer',
            'equipment_id'   => 'required|integer',
            'status'         => 'required|alpha_num|min:2|max:50',
            'quantity'       => 'required|alpha_num|min:2|max:50',
            'responsibility' => 'required|alpha_num|min:2|max:50',
            'first_use_date' => 'required|alpha_num|min:2|max:50',
            'last_use_date'  => 'required|alpha_num|min:2|max:50',
            'obtained'       => 'required|alpha_num|min:2|max:50',
        ];

        $this->validate($request, $rules);

        $chunk->fill($request->only(['obtained']));

        if ($chunk->isClean()) {
            return $this->errorResponse('You need to a specify different value to update!', 422);
        }

        $chunk->save();
        return $this->showOne($chunk);
    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chunk $chunk)
    {
        $chunk->delete();
        return $this->showOne($chunk);
    }


}
