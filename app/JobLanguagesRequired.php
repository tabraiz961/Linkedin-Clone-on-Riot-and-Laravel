<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\JobSkills;

class JobLanguagesRequired extends Model {
	protected $table = 'job_languages_req';
	public $timestamps = false;
	protected $primaryKey = 'id';
	

	
	public function languagesName() {
		return $this->hasOne(Languages::class, 'id', 'language_id');
	}
}