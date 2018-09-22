<?php

namespace App\Http\Controllers\Equipment;

use App\Equipment;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class EquipmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipments = Equipment::all();

        return $this->showAll($equipments);
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
            'name'             => 'required|alpha_num|min:2|max:50',
            'specific_number'  => 'required|integer||digits:8',
            'size_json'        => 'required|json|max:999999',
            'rules_paper'      => 'required|mimetypes:application/pdf,image/jpeg,image/jpg,image/png|between:10,3000',
        ];

        $this->validate($request, $rules);

        $newEquipment = Equipment::create($request->all());
        return $this->showOne($newEquipment,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        return $this->showOne($equipment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment)
    {

        $rules = [
            'name'             => 'alpha_num|min:2|max:50',
            'specific_number'  => 'integer||digits:8',
            'size_json'        => 'json|max:999999',
            'rules_paper'      => 'mimetypes:application/pdf, image/jpeg, image/png|between:10,3000',
        ];

        $this->validate($request, $rules);

        $equipment->fill($request->only([
            'name' ,
            'specific_number',
            'size_json',
            'rules_paper',
        ]));
        if ($equipment->isClean()) {
            return $this->errorResponse('You need to specify a different value for update.', 422);
        }

        $equipment->save();
        return $this->showOne($equipment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return $this->showOne($equipment);
    }
}
