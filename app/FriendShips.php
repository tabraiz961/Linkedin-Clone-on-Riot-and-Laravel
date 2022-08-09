<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use DB;

/**
 * App\User
 *
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class FriendShips extends Model { 
	use Notifiable;

	protected $table = 'friendships';
	// public $timestamps = true;

	// const CREATED_AT = 'users.created_at';
	// const UPDATED_AT = 'users.updated_at';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'friend1_id',
		'friend2_id',
	];



	const select = [
		'friend1_id',
		'friend2_id',
	];

	const select_more = [
		'created_at',
		'updated_at'
	];

	const searchable_fields = [ 'friend1_id', 'friend2_id', ];

	// const default_photo_url = '/images/avatar.png';

	// Max usage in MB
	const quota_usage_max = 200;



	public function scopeSelectMutualFriends() {
		return FriendShips::join( 'friendships as friendships2', 'friendships2.friend1_id', '=', 'friendships.friend2_id' )
			->where( 'friendships.friend1_id', '=', Auth::user()->user_id )
			->where( 'friendships2.friend2_id', '!=', Auth::user()->user_id )
			->select( 'friendships2.friend2_id as user_id', DB::raw('count(friendships.friend2_id) as mutuals_count')  )
			->distinct()
			->groupBy('friendships2.friend2_id')
			->get();
	}

	public function scopeSelectFriends( $query, $user_id=null) {
		
		$user_id = $user_id ? $user_id : Auth::user()->user_id;
		return $query->select('friendships.friend2_id')
				->where( 'friendships.friend1_id', $user_id )
				->get();
	}
	public function scopeSelectCurrentWorkingFriendsByMyInstitute() {
		$profileExperiences = Auth::user()->profile->profileExperiences->first();
		if($profileExperiences){
			$institute_id = $profileExperiences['institution_id']; 
			return FriendShips::select('friendships.friend2_id as friend', 'profile_experience.profile_id')
				->join('profile_details', 'profile_details.user_id', '=', 'friendships.friend2_id')
				->join('profile_experience', 'profile_details.profile_id', '=', 'profile_experience.profile_id')
				->where( 'friendships.friend1_id', Auth::user()->user_id )
				->where( 'profile_experience.institution_id', $institute_id )
				->where( 'profile_experience.is_currently_engaged', 1 )
				->get();
		}
	}
		 
}