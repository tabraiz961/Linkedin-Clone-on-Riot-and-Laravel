<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplied extends Model {
	//use Searchable;
	protected $table = 'job_applied';
	public $timestamps = false; 
	protected $primaryKey = 'id';
	
}