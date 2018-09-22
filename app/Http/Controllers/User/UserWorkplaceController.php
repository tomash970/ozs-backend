<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserWorkplaceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $workplaces = $user->transactions()->with('workplace')->get()->pluck('workplace');
        return $this->showAll($workplaces);
    }

}
