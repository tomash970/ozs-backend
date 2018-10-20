<?php

namespace App;

use App\Position;
use App\Role;
use App\Transaction;
use App\Transformers\UserTransformer;
use App\Unit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
 
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $table = 'users';

    protected $dates =['deleted_at'];

    public $transformer = UserTransformer::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'first_name',
        'last_name',
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
        'unit_id',
        'position_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token'
    ];



    
/*----------------------POČETAK SEKCIJE MUTATORA I ACCEDSSORA KOJI SLUŽE ZA PRETVARANJE VELIKIH SLOVA U MALA PRI SPREMANJU U BAZU I PRI VRAČANJU VRAČAJU POČETNA SLOVA NA VELIKO -------------------*/
    public function setNameAttribute($name) 
    {
        $this->attributes['name'] = strtolower($name);
    }
    
    public function getNameAttribute($name) 
    {
        return ucwords($name);
    }
    
    
    public function setEmailAttribute($email) 
    {
        $this->attributes['email'] = strtolower($email);
    }

    /*----------------------KRAJ SEKCIJE -------------------*/



    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return str_random(40);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

}
