<?php

use App\Chunk;
use App\Equipment;
use App\Position;
use App\Role;
use App\Transaction;
use App\Unit;
use App\User;
use App\Workplace;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Equipment::truncate();
        Transaction::truncate();
        Unit::truncate();
        Chunk::truncate();
        Workplace::truncate();
        Role::truncate();
        Position::truncate();
        DB::table('equipment_workplace')->truncate();


        $usersQuantity       = 30;
        $unitQuantity        = 12;
        $transactionQuantity = 60;
        $workplaceQuantity   = 15;
        $equipmentQuantity   = 30;
        $chunksQuantity      = 350;
        $roleQuantity        = 5;
        $positionQuantity    = 10;

        factory(Position::class, $positionQuantity)->create();

        factory(Unit::class, $unitQuantity)->create();

        factory(Role::class, $roleQuantity)->create();

        factory(User::class, $usersQuantity)->create()->each(
            function ($user) {

                $role = Role::all()->random(mt_rand(1, 5))->pluck('id');

                $user->roles()->attach($role);
            }
        );

        factory(Workplace::class, $workplaceQuantity)->create();

        factory(Equipment::class, $equipmentQuantity)->create()->each(
            function ($equipment) {

                $workplace = Workplace::all()->random(mt_rand(1, 5))->pluck('id');

                $equipment->workplaces()->attach($workplace);
            }
        );

        factory(Transaction::class, $transactionQuantity)->create();

        factory(Chunk::class, $chunksQuantity)->create();
    }
}
