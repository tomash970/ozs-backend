<?php

namespace App;

use App\Chunk;
use App\Workplace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $fillable = [
    	'name',
    	'specific_number',
    	'size_json',
    	'rules_paper'
    ];

    public function chunks()
    {
    	return $this->hasMany(Chunk::class);
    }

    public function workplaces()
    {
        return $this->belongsToMany(Workplace::class);
    }

   
}
