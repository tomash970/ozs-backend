<?php

namespace App\Http\Controllers\Equipment;

use App\Equipment;
use App\Http\Controllers\ApiController;
use App\Workplace;
use Illuminate\Http\Request;

class EquipmentWorkplaceController extends ApiController
{

    public function __construct()
    {
      $this->middleware('client.credentials')->only(['index']);
      $this->middleware('auth:api')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Equipment $equipment)
    {
        $workplaces = $equipment->workplaces;
        return $this->showAll($workplaces);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment, Workplace $workplace)
    {
        $equipment->workplaces()->syncWithoutDetaching([$workplace->id]);
        return $this->showAll($equipment->workplaces);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment, Workplace $workplace)
    {
        if (!$equipment->workplaces()->find($workplace->id)) {
            return $this->errorResponse('The specified workplace has not have this equipment', 404);
        }

        $equipment->workplaces()->detach($workplace->id);
        return $this->showAll($equipment->workplaces);
    }
}
