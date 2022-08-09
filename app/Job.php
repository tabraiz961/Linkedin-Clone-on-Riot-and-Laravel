<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\JobSkills;

class Job extends Model {
	protected $table = 'jobs';
	public $timestamps = false;
	protected $primaryKey = 'id';
	
    public function skills()
    { 
        return $this->hasMany(JobSkills::class, 'job_id', 'id');
    }
	
    public function languages()
    { 
        return $this->hasMany(JobLanguagesRequired::class, 'job_id', 'id');
    }
}