<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model {
	protected $table = 'search_history';
	public $timestamps = false;
	protected $primaryKey = 'id';
	

}