<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
*Units
**/
Route::apiResource('units', 'Unit\UnitController', ['except' =>['create', 'edit']]); 
Route::apiResource('units.transactions', 'Unit\UnitTransactionController', ['only' =>['index']]); 
Route::apiResource('units.users', 'Unit\UnitUserController', ['only' =>['index']]); 
Route::apiResource('units.positions', 'Unit\UnitPositionController', ['only' =>['index']]); 
Route::apiResource('units.chunks', 'Unit\UnitChunkController', ['only' =>['index']]); 


/**
*Transactions
**/
Route::apiResource('transactions', 'Transaction\TransactionController', ['only' =>['index', 'show']]);
Route::apiResource('transactions.equipments', 'Transaction\TransactionEquipmentController', ['only' =>['index']]);
Route::apiResource('transactions.users', 'Transaction\TransactionUserController', ['only' =>['index']]);
Route::apiResource('transactions.chunks', 'Transaction\TransactionChunkController', ['only' =>['index']]);
Route::apiResource('transactions.workplaces', 'Transaction\TransactionWorkplaceController', ['only' =>['index']]);
Route::apiResource('transactions.units', 'Transaction\TransactionUnitController', ['only' =>['index']]);
Route::apiResource('transactions.users.chunks', 'Transaction\TransactionUserChunkController', ['only' =>['store', 'update', 'destroy']]);



/**
*Equipment
**/
Route::apiResource('equipments', 'Equipment\EquipmentController', ['except' =>['create', 'edit']]);
Route::apiResource('equipments.transactions', 'Equipment\EquipmentTransactionController', ['only' =>['index']]);
Route::apiResource('equipments.workplaces', 'Equipment\EquipmentWorkplaceController', ['only' =>['index', 'update', 'destroy']]);


/**
*Work_places
**/
Route::apiResource('workplaces', 'Workplace\WorkplaceController', ['except' =>['create', 'edit']]);
Route::apiResource('workplaces.equipments', 'Workplace\WorkplaceEquipmentController', ['only' =>['index']]);
Route::apiResource('workplaces.transactions', 'Workplace\WorkplaceTransactionController', ['only' =>['index']]);
Route::apiResource('workplaces.users', 'Workplace\WorkplaceUserController', ['only' =>['index']]);
Route::apiResource('workplaces.units', 'Workplace\WorkplaceUnitController', ['only' =>['index']]);


/**
*Users
**/
Route::apiResource('users', 'User\UserController', ['except' =>['create', 'edit']]);
Route::apiResource('users.transactions', 'User\UserTransactionController', ['except' =>['create', 'edit']]);
Route::apiResource('users.units', 'User\UserUnitController', ['only' =>['index']]);
Route::apiResource('users.positions', 'User\UserPositionController', ['only' =>['index']]);
Route::apiResource('users.roles', 'User\UserRoleController', ['only' =>['index']]);
Route::apiResource('users.chunks', 'User\UserChunkController', ['only' =>['index']]);
Route::apiResource('users.workplaces', 'User\UserWorkplaceController', ['only' =>['index']]);
Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');


/**
*Chunks
**/
Route::apiResource('chunks', 'Chunk\ChunkController', ['only' =>['index', 'show']]);
Route::apiResource('chunks.transactions', 'Chunk\ChunkTransactionController', ['only' =>['index']]);
Route::apiResource('chunks.users', 'Chunk\ChunkUserController', ['only' =>['index']]);
Route::apiResource('chunks.equipments', 'Chunk\ChunkEquipmentController', ['only' =>['index']]);


/**
*Roles
**/
Route::apiResource('roles', 'Role\RoleController', ['except' =>['create', 'edit']]);
Route::apiResource('roles.users', 'Role\RoleUserController', ['only' =>['index']]);
Route::apiResource('roles.units', 'Role\RoleUnitController', ['only' =>['index']]);
Route::apiResource('roles.positions', 'Role\RolePositionController', ['only' =>['index']]);


/**
*Position
**/
Route::apiResource('positions', 'Position\PositionController', ['except' =>['create', 'edit']]);
Route::apiResource('positions.users', 'Position\PositionUserController', ['only' =>['index']]);
Route::apiResource('positions.units', 'Position\PositionUnitController', ['only' =>['index']]);
Route::apiResource('positions.transactions', 'Position\PositionTransactionController', ['only' =>['index']]);


/**
*Manager
**/
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken'); 
