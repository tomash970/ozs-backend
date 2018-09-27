<?php

namespace App;

use App\Equipment;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chunk extends Model
{
	use SoftDeletes;

	const NO_RESPONSIBILITY      = '0';
	const PARTIAL_RESPONSIBILITY = '1';
	const FULL_RESPONSIBILITY    = '2';

	//MARKS FOR status
    const EXCEPTIONAL   = '0';
    const EXTRAORDINARY = '1';

    const OBTAINED     = '1';
    const NOT_OBTAINED = '0';
    
    protected $dates = ['deleted_at'];
    protected $fillable = [
    	'transaction_id',
        'equipment_id',
        'status',
        'quantity',
        'responsibility',
        'first_use_date',
        'last_use_date',
        'obtained',
    ];

    public function isResponsible() 
    {
    	if ($this->responsibility == Chunk::FULL_RESPONSIBILITY) {
    		return $this->responsibility == Chunk::FULL_RESPONSIBILITY;
    	}elseif ($this->responsibility == Chunk::PARTIAL_RESPONSIBILITY) {
    		return $this->responsibility == Chunk::PARTIAL_RESPONSIBILITY;
    	}
    	return $this->responsibility == Chunk::NO_RESPONSIBILITY;
    }

    // public function isPartialResponsible() 
    // {
    // 	return $this->responsibility == Chunk::PARTIAL_RESPONSIBILITY;
    // }

    public function isObtained(){
        return $this->status == Chunk::OBTAINED;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

}
