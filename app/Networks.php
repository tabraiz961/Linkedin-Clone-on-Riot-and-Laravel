<?php

namespace App;

use App\Networks as AppNetworks;
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
class Networks extends Model {
	use Notifiable;

	protected $table = 'networks';
	public $timestamps = true;

	const CREATED_AT = 'users.created_at';
	const UPDATED_AT = 'users.updated_at';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'surname',
		'username',
		'email',
		'password',
		'role',
		'birth_date',
		'title',
		'cv_url',
		'photo_id',
		'cover_id',
		'infos',
		'remember_token'
	];



	const select = [
		'user1_id',
		'user2_id',
	];

	const select_more = [
		'created_at',
		'updated_at'
	];

	const searchable_fields = [ 'user1_id', 'user2_id', ];

	// const default_photo_url = '/images/avatar.png';

	// Max usage in MB
	const quota_usage_max = 200;

	// Could not find usage 
	public function scopeSelectorNetworkMembers() {
		return Networks::join( 'networks as networks2', 'networks2.user1_id', '=', 'networks.user2_id' )
			->where( 'networks.user1_id', '=', Auth::user()->user_id )
			->where( 'networks2.user2_id', '!=', Auth::user()->user_id )
			->select( 'networks2.user2_id as user_id', DB::raw('count(networks.user2_id) as mutuals_count')  )
			->distinct()
			->groupBy('networks2.user2_id')
			->get();
	}

	
}