<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();

        Passport::tokensCan([
            'manage-account' => 'redad yout accountdata, id, name, email, if verified, and if acimd(cannot read password). Modity your account data (email, and password) Cannot delete your account',
            'read-general' => 'Read general information like Units, Workplaces, Positions and Equipments',
            'manage-transactions' => 'Create transactions for specific user',
            'manage-equipment' => 'Create, update and delete equipment for specific user',
            'manage-units' => 'Create, update and delete units for specific user',
            'manage-roles' => 'Create, update and delete roles for specific user',
            'manage-workplace' => 'Create, update and delete workplaces ',
            'manage-position' => 'Create, update and delete position for specific user',

        ]);
    }
}
