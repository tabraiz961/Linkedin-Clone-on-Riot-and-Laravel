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
class Addresses extends Model { 
	use Notifiable;

	protected $table = 'uscities';
	// public $timestamps = true;

	// const CREATED_AT = 'users.created_at';
	// const UPDATED_AT = 'users.updated_at';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';




	const select = [
		'id',
		'city',
		'city_ascii',
		'state_id',
		'state_name',
		'county_name',
	];

	// const select_more = [
	// 	'created_at',
	// 	'updated_at'
	// ];

	const searchable_fields = [ 'id', 'city', 'city_ascii', 'state_id', 'state_name', 'county_name',];

	// const default_photo_url = '/images/avatar.png';

	// Max usage in MB
	const quota_usage_max = 200;

}