<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Nickname extends Model
{
    protected $table = 'nick_name';
    public $timestamps = false;
	
	
	public function camps() {
        return $this->hasMany('App\Model\Camp', 'nick_name_id', 'nick_name_id');
    }
}
