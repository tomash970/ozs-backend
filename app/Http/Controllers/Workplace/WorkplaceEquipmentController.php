<?php

namespace App\Http\Controllers\Workplace;

use App\Workplace;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class WorkplaceEquipmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Workplace $workplace)
    {
        $equipments = $workplace->equipments;
        return $this->showAll($equipments);
    }
}
