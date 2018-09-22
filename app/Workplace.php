<?php

namespace App;

use App\Equipment;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workplace extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
    	'name',
    	'specific_number',
    ];

    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class);
    }


    
}
