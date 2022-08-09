<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Skills extends Model {
	protected $table = 'skills';
	public $timestamps = false;
	protected $primaryKey = 'id';
	
   
}