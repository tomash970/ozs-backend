<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\ApiController;
use App\Position;
use Illuminate\Http\Request;

class PositionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::all();

        return $this->showAll($positions);
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
            'name' => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50'
        ];

        $this->validate($request, $rules);
        $newPosition = Position::create($request->all());
        return $this->showOne($newPosition);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        return $this->showOne($position);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
         $rules = [
            'name' => 'alpha_num|min:2|max:50'
        ];

        $this->validate($request, $rules);

        $position->fill($request->only(['name']));
        if ($position->isClean()) {
           return $this->errorResponse('You need to specify a different value to update!', 422);
        }

        $position->save();
        return $this->showOne($position);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        $position->delete();
        return $this->showOne($position);
    }
}
