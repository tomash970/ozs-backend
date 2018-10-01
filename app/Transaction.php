<?php

namespace App;

use App\Chunk;
use App\Equipment;
use App\Transformers\TransactionTransformer;
use App\Unit;
use App\Workplace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

	const CONFIRMED     = '1';
    const NOT_CONFIRMED = '0';

    const ORDER_ACCEPTED     = '1';
    const NOT_ORDER_ACCEPTED = '0';
    
    protected $dates = ['deleted_at'];

    public $transformer = TransactionTransformer::class;

    protected $fillable =[	  
		'worker_name', 
        'worker_first_name',
        'worker_last_name',
		'user_id',
        'confirmation',
        'order_accepted',
        'workplace_id',
    ];

    public function isConfirmed(){
        return $this->confirmation == Transaction::CONFIRMED;
    }

    public function isOrderAccepted(){
        return $this->order_accepted == Transaction::ORDER_ACCEPTED;
    }

    public function workplace()
    {
    	return $this->belongsTo(Workplace::class);
    }

    public function equipments()
    {
    	return $this->belongsToMany(Equipment::class);
    }

    public function unit()
    {
    	return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chunks()
    {
        return $this->hasMany(Chunk::class);
    }

}
