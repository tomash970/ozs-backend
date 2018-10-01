<?php

namespace App;

use App\Transaction;
use App\Transformers\UnitTransformer;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $transformer = UnitTransformer::class;

    protected $fillable = [
		'name',
		'city',
		'street',
		'street_number',
    ];

    public function users()
    {
    	return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
