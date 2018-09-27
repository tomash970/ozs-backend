<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $rules  = [
            'name' => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|min:6|confirmed',
            'unit_id' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
        
        $user = User::create($data);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {


        $rules = [
            'name' => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:50',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];
        $this->validate($request, $rules);

         if($request->has('name')){
            $user->name = $request->name;
        }

        
        
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }
        
        if($request->has('password') ){
            $user->password = bcrypt($request->password);
        }
        
        if($request->has('admin')){
          if(!$user->isVerified()){
            return $this->errorResponse( 'Only verified users can modify the admin field',  409);
            // return response()->json(['error' => 'Only verified users can modify the admin field', 'code'=> 409], 409);
          }
            $user->admin = $request->admin;
        }

          if($request->has('unit_id')){
            $user->unit_id = $request->unit_id;
        }
        
        
        if(!$user->isDirty()){
            return $this->errorResponse('You neeed to specify a different value to update',  422);
            //return response()->json(['error' => 'You neeed to specify a different value to update', 'code'=> 422], 422);
        }                
        
        $user->save();
        
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }


}
