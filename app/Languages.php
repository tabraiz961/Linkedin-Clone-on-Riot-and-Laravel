<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\JobSkills;

class Languages extends Model {
	protected $table = 'languages';
	public $timestamps = false;
	protected $primaryKey = 'id';
	
}