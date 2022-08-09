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

class ProfileExperience extends Authenticatable {
	use Notifiable;

	protected $table = 'profile_experience';
	public $timestamps = false;

	protected $primaryKey = 'experience_id';
	public $incrementing = true;



	const select = [
		'experience_id',
		'profile_id',
		'institution_id',
		'experience_title',
		'start',
		'end',
		'priority_id',
		// 'description',
		'address_id',
		'experience_parent_id',
	];

	// const select_more = [
	// 	'created_at',
	// 	'updated_at'
	// ];

	const searchable_fields = [ 
		'experience_id',
		'profile_id',
		'institution_id',
		'experience_title',
		'start',
		'end',
		'priority_id',
		// 'description',
		'address_id',
		'experience_parent_id',
	];


	// Max usage in MB
	const quota_usage_max = 200;

	
	public function profileDetails()
    {
        return $this->belongsTo(ProfileDetails::class, 'user_id', 'profile_id');
    }
}