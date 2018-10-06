<?php

namespace App\Http\Controllers\Workplace;

use App\Http\Controllers\ApiController;
use App\Transformers\WorkplaceTransformer;
use App\Workplace;
use Illuminate\Http\Request;

class WorkplaceController extends ApiController
{

    public function __construct()
    {
      parent::__construct();

      $this->middleware('transform.input:' . WorkplaceTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workplaces = Workplace::all();

        return $this->showAll($workplaces);
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
            'name'            => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'specific_number' => 'required|integer|digits:6',
        ];

        $this->validate($request, $rules);
        $newWorkplace = Workplace::create($request->all());
        return $this->showOne($newWorkplace, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Workplace $workplace)
    {
        return $this->showOne($workplace);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workplace $workplace)
    {
        $rules = [
            'name'            => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'specific_number' => 'integer|digits:6',
        ];

        $this->validate($request, $rules);

        $workplace->fill($request->only([
            'name',
            'specific_number'
        ]));

        if ($workplace->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }

        $workplace->save();
        return $this->showOne($workplace);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workplace $workplace)
    {
        $workplace->delete();

        return $this->showOne($workplace);
    }
}
