<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $rules  = [
            //'name'       => 'required|regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:60',
            'first_name' => 'required|regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30',
            'last_name'  => 'required|regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30',
            'email'      => 'required|email|unique:users|max:50',
            'password'   => 'required|min:6|confirmed',
            'unit_id'    => 'required|integer',
        ];

        $this->validate($request, $rules);

        $data = $request->all(); 
        $data['name']               = $request->first_name . ' ' . $request->last_name;
        $data['password']           = bcrypt($request->password);
        $data['verified']           = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin']              = User::REGULAR_USER;
        
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
            //'name'       => 'regex:/(^([a-žA-Ž ]+)(\d+)?$)/u|min:2|max:60',
            'first_name' => 'regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30',
            'last_name'  => 'regex:/(^([a-žA-Ž -]+)(\d+)?$)/u|min:2|max:30',
            'email'      => 'email|unique:users,email,' . $user->id,
            'password'   => 'min:6|confirmed',
            'admin'      => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];
        $this->validate($request, $rules);

        if(!$request->has('first_name') && $request->has('last_name')){
            $user->name       = $user->first_name . ' ' . $request->last_name;
            //$user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
        }

        if($request->has('first_name') && !$request->has('last_name')){
            $user->name = $request->first_name . ' ' . $user->last_name;
            $user->first_name = $request->first_name;
            //$user->last_name  = $request->last_name;
        }

        if($request->has('first_name') && $request->has('last_name')){
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
        }
        
        if($request->has('email') && $user->email != $request->email){
            $user->verified           = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email              = $request->email;
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

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();
        return $this->showMessage('The account has been verified successfully');

    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This use already verified', 409);
        }

        retry(5, function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        
        return $this->showMessage('The verification email has been resend');
    }


}
