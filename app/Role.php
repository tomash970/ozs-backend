<?php

namespace App;

use App\Transaction;
use App\Transformers\RoleTransformer;
use App\Unit;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

    public $transformer = RoleTransformer::class;

    protected $fillable =[	  
		'name',  
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

        public function units()
    {
        return $this->belongsToMany(Unit::class);
    }
}
