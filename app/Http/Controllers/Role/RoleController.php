<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\ApiController;
use App\Role;
use App\Transformers\RoleTransformer;
use Illuminate\Http\Request;

class RoleController extends ApiController
{

    public function __construct()
    {
      $this->middleware('client.credentials')->only(['index', 'show']);
      $this->middleware('auth:api')->except(['index', 'show']);
      $this->middleware('transform.input:' . RoleTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return $this->showAll($roles);
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
        $newRole = Role::create($request->all());
        return $this->showOne($newRole);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return $this->showOne($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
       $rules = [
           'name' => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50'
       ];
       $this->validate($request, $rules);

       $role->fill($request->only(['name']));
       if ($role->isClean()) {
           return $this->errorResponse('You need to specify a different value to update!', 422);
       }
       $role->save();
       return $this->showOne($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->showOne($role);
    }
}
