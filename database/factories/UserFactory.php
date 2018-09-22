<?php

use App\Chunk;
use App\Equipment;
use App\Position;
use App\Role;
use App\Transaction;
use App\Unit;
use App\User;
use App\Workplace;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'name'          => $faker->company,
        'city'          => $faker->city,
        'street'        => $faker->streetName,
        'street_number' => $faker->numberBetween(1, 100),
    ];
});

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(Position::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(User::class, function (Faker $faker) {
	$unit = Unit::all()->random();
	$position = Position::all()->random();

    return [
        'name'               => $faker->name,
        'email'              => $faker->unique()->safeEmail,
        'password'           => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token'     => str_random(10),
        'verified'           => $verified = $faker->randomElement(([User::VERIFIED_USER, User::UNVERIFIED_USER])),
        'verification_token' => $verified == User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin'              => $verified = $faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
        'unit_id'            => $unit->id,
        'position_id'        => $position->id,
    ];
});

$factory->define(Equipment::class, function (Faker $faker) {

    return [
        'name'            => $faker->word,
        'specific_number' => $faker->randomNumber(8),
        'size_json'       => $faker->randomElement(['{"s":"54", "m":"56", "l":{"xl":"58", "xxl":"60"}}', '{"s":"54", "m":"56", "l":"58"}', '{"s":"44", "m":"46", "l":"48"}']),
        'rules_paper'     =>  $faker->randomElement(['1.pdf', '2.pdf' ,'3.pdf']),

    ];
});

$factory->define(Workplace::class, function (Faker $faker) {
    return [
        'name'            => $faker->jobTitle,
        'specific_number' => $faker->randomNumber(6),
    ];
});



$factory->define(Transaction::class, function (Faker $faker) {

  $user      = User::all()->random();
    //$unit      = Unit::all()->random();
	$workplace = Workplace::all()->random();

	$confirm_faker = $faker->randomElement([
                                          Transaction::CONFIRMED, 
                                          Transaction::NOT_CONFIRMED, 
                                         ]); 
	$order_faker =  $faker->randomElement([
                                          Transaction::ORDER_ACCEPTED, 
                                          Transaction::NOT_ORDER_ACCEPTED,
                                         ]);
		
    return [
    	'worker_name'    => $faker->name,
    	'user_id'        => $user->id,
        // 'superior_id'    => $faker->numberBetween(1, 10),
        // 'manager_id'    => $faker->numberBetween(11, 20),
        // 'obtainer_id'    => $faker->numberBetween(21, 30),
        'workplace_id'   => $workplace->id,
        'confirmation'   => $confirm_faker,
        'order_accepted' => $order_faker,

    ];
});


$factory->define(Chunk::class, function (Faker $faker) {

    $transaction = Transaction::all()->random();
    $equipment   = Equipment::all()->random();

    return [
        'transaction_id'   => $transaction->id,
        'equipment_id'     => $equipment->id,
        //'obtainer_id'      => $faker->numberBetween(21, 30),
        'status'           => $faker->randomElement([
                                          Chunk::EXCEPTIONAL, 
                                          Chunk::EXTRAORDINARY, 
                                         ]),
        'quantity'         => $faker->numberBetween(1, 5),
        'responsibility'   => $faker->randomElement([
                                          Chunk::FULL_RESPONSIBILITY, 
                                          Chunk::PARTIAL_RESPONSIBILITY, 
                                          Chunk::NO_RESPONSIBILITY
                                         ]),
        'first_use_date'   => $firstDate = $faker->dateTimeBetween('2016-01-01 00:00:00', '2018-12-31 23:59:59'),
        'last_use_date'    => $faker->dateTimeBetween( $firstDate, '2019-12-31 23:59:59'),
        'obtained'         => $faker->randomElement([
                                          Chunk::OBTAINED, 
                                          Chunk::NOT_OBTAINED, 
                                         ]),
    ];
});
