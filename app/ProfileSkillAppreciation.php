<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class ProfileSkillAppreciation extends Model {
	//use Searchable;
	protected $table = 'profile_skills_appreciation';
	protected $primaryKey = 'id';
	public $incrementing = true;

	
	public function SkillOwner() {
		return $this->belongsTo(ProfileSkill::class, 'profile_skill_id', 'id');
	}
}