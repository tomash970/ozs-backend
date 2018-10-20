<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserRoleController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {

        // if ($user->roles->contains('name', 'boss')) {
        //     $roles = $user->roles;
        //     return $this->showAll($roles);
        // }
        $roles = $user->roles;
        return $this->showAll($roles);
        //return "jebo";
    }

}
