<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserChunkController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $chunks = $user->transactions()->with('chunks')->get()->pluck('chunks')->collapse();
        return $this->showAll($chunks);
    }

}
