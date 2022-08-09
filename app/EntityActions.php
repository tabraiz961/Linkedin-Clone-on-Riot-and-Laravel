<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class EntityActions extends Model {
	protected $table = 'entity_actions';
	public $timestamps = false;
	protected $primaryKey = 'id';
	
   
}