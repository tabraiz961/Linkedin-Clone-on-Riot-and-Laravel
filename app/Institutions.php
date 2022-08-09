<?php

namespace App;

use App\Institutions as AppInstitutions;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\FriendRequestReceived;
use App\Notifications\FriendRequestAccepted;
use Laravel\Scout\Searchable;

class Institutions extends Authenticatable {
	use Notifiable;

	protected $table = 'institution';
	public $timestamps = false;

	protected $primaryKey = 'institution_id';
	public $incrementing = true;





	const select = [
		'institution_id ',
		'owner_id',
		'institution_name',
	];

	// const select_more = [
	// 	'created_at',
	// 	'updated_at'
	// ];

	const searchable_fields = [ 
		'institution_id ',
		'owner_id',
		'institution_name',
	];


	// Max usage in MB
	const quota_usage_max = 200;

	
	public function address() {
		return $this->hasOne( 'App\Addresses', 'id', 'address_id' );
	}
	
	public function typee() {
		return $this->hasOne( 'App\InstitutionType', 'type_id', 'institution_type_desc' );
	}
	
	public function owner()
    {
        return $this->belongsTo('App\ProfileExperience', 'profile_id', 'profile_id');
    }
	
	public function followers()
    {
        return $this->hasMany('App\InstitutionFollows', 'institution_id', 'institution_id');
    }
	public function experiences()
    {
        return $this->belongsToMany('App\ProfileExperience', 'institution', 'institution_id', 'institution_id');
    }
	
	public function jobs_placed()
    {
        return $this->hasMany(JobOffer::class,  'institution_id', 'institution_id');
    }
	// public function getMyFriendsWorkingInInstitution($institutionId = 4)
	// {
	// 	$friends = array_column(Auth::user()->getFriends()->toArray(),'user_id');
			
	// }

	public function getMyFriendsWorkingInInstitution($institution)
	{
		// Get Connections/Friends Working in Institutions
        $friendsId = array_column(Auth::user()->getFriends()->toArray(),'user_id');
        $profileIds = array_column(ProfileDetails::whereIn('user_id', $friendsId)->get()->toArray(),'profile_id');
		if(count($profileIds)){
			return	count(ProfileExperience::select('profile_id')
			->where('institution_id', $institution['institution_id'])
			->whereIn('profile_id', $profileIds)
			->where('is_currently_engaged', 1)
			->distinct()
			->get());
		}
	}

	public function getUsersWorkingInInstitution($institution)
	{
		return	count(ProfileExperience::select('profile_id')
		->where('institution_id', $institution['institution_id'])
		->where('is_currently_engaged', 1)
		->distinct()
		->get());
	}

	
    
}