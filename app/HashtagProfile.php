<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\JobSkills;

class HashtagProfile extends Model {
	protected $table = 'hashtag_profile';
	public $timestamps = false;
	protected $primaryKey = 'id';
	
}