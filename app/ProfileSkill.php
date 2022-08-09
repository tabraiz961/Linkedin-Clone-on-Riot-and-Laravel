<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class ProfileSkill extends Model {
	//use Searchable;
	protected $table = 'profile_skills';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;

	public function skillName() {
		return $this->hasOne(Skills::class, 'id', 'skill_id');
	}
	  
	public function appreciators() {
		return $this->hasMany(ProfileSkillAppreciation::class, 'profile_skill_id', 'id');
	}
	// public function profileSkills()
    // {
    //     return $this->hasMany(ProfileSkill::class, 'profile_id', 'profile_id');
    // }

}