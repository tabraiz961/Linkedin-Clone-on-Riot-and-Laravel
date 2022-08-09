<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplyDetails extends Model {
	protected $table = 'job_applied_details';
	public $timestamps = false;
	protected $primaryKey = 'id';
	
}