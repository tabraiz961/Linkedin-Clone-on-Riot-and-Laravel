<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\FriendRequestReceived;
use App\Notifications\FriendRequestAccepted;
use Laravel\Scout\Searchable;

class ProfileDetails extends Authenticatable {
	use Notifiable;

	protected $table = 'profile_details';
	public $timestamps = false;

	protected $primaryKey = 'profile_id';
	public $incrementing = true;




	/**
	 * The attributes that should be validated and their respective format
	 */
	const validation = [
		'short_bio' => 'string|max:500',
		'long_description' => 'string|max:2000',
	];


	const select = [
		'profile_id',
		'user_id',
		'short_bio',
		'long_description',
	];

	// const select_more = [
	// 	'created_at',
	// 	'updated_at'
	// ];

	const searchable_fields = [ 
		'profile_id',
		'user_id',
		'short_bio',
		'long_description'
	];


	// Max usage in MB
	const quota_usage_max = 200;

	
	public function profileExperiences()
    { 
        return $this->hasMany('App\ProfileExperience', 'profile_id', 'profile_id');
    }
	public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
	public function profileSkills()
    {
        return $this->hasMany(ProfileSkill::class, 'profile_id', 'profile_id');
    }
}