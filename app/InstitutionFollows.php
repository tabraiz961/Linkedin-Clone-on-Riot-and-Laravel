<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class InstitutionFollows extends Model {
	use Notifiable;

	protected $table = 'institution_follows';
    public $timestamps = false;

    protected $primaryKey = 'id';
    public $incrementing = false;


	public function getUsersFollowingInstitution($institution, $user = [])
    {   
        $connectionsCount = 0;
        $connections = Auth::user()->getFriends()->toArray();
        if(count($connections)){
            $connectionsCount =  InstitutionFollows::where('institution_id', $institution['institution_id'])
                                ->whereIn('user_id', $user)
                                ->count();
        }
        return $connectionsCount;
    }
    public function scopeUserFollowing($query, $institutionId, $userId=null)
    {
        $userId = $userId ? $userId : Auth::user()->user_id;
        return $query
            ->where( 'institution_follows.user_id', $userId )
            ->where( 'institution_follows.institution_id', $institutionId )
		    ->get();
    }
}