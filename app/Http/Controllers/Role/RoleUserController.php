<?php

namespace App\Http\Controllers\Role;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class RoleUserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $users = $role->users;
        return $this->showAll($users);
    }

}
