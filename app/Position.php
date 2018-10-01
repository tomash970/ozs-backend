<?php

namespace App;

use App\Transaction;
use App\Transformers\PositionTransformer;
use App\Unit;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

    public $transformer = PositionTransformer::class;

    protected $fillable =[	  
		'name',  
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

        public function units()
    {
        return $this->belongsToMany(Unit::class);
    }
}
