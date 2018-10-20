<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\ApiController;
use App\Transformers\UnitTransformer;
use App\Unit;
use Illuminate\Http\Request;

class UnitController extends ApiController
{

    public function __construct()
    {
      $this->middleware('client.credentials')->only(['index', 'show']);
      $this->middleware('auth:api')->except(['index', 'show']);
      $this->middleware('transform.input:' . UnitTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all();

        return $this->showAll($units);
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
            'name'          => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'city'          => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'street'        => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'street_number' => 'required|integer|max:999',
        ];

        $this->validate($request, $rules);
        $newUnit = Unit::create($request->all());
        return $this->showOne($newUnit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        return $this->showOne($unit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $rules = [
           'name'          => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
           'city'          => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
           'street'        => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
           'street_number' => 'integer|max:999', 
        ];
        $this->validate($request, $rules);

        $unit->fill($request->only([
           'name',
           'city',
           'street',
           'street_number', 
        ]));
        if ($unit->isClean()) {
            return $this->errorResponse('You need to specify a different value to update.', 422);
        }
        $unit->save();
        return $this->showOne($unit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return $this->showOne($unit);
    }
}
