<?php

namespace App\Http\Controllers;


use App\User;
use App\Http\Controllers\UserController;
use App\FriendShips;
use App\Http\Controllers\NetworkMembersController as ControllersNetworkMembersController;
use App\InstitutionFollows;
use App\Institutions;
use App\Networks;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\GotFollowForInstitution;
use App\ProfileExperience;
use App\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use DB;

class NetworkMembersController extends Controller
{
    public function get() {
	 	
		$friendrequests = NetworkMembersController::getRequestsSentReceivedUsers();
        
		$mutualFriends = FriendShips::selectMutualFriends()->toArray(); 
		$mutualFriendslen = sizeof($mutualFriends);
		$users = NetworkMembersController::filterMutualFriendsWithOutNetworksRequests([], $friendrequests, $mutualFriends);
		
		// Include same address People
		if($mutualFriendslen < 8 ){
			$myfriends = FriendShips::where( 'friendships.friend1_id', '=', Auth::user()->user_id )
				->get(['friend2_id'])
				->toArray();
			$excludeIdsAddress = [];
			if(count($myfriends)){ 
				// Exclude my friend ids from address
				$excludeIdsAddress = array_merge($excludeIdsAddress, array_column($myfriends, 'friend2_id'));
				// Exclude my friend ids from Requests
				$excludeIdsAddress = array_merge($excludeIdsAddress, $friendrequests);
				// Exclude my Mutual friend ids 
				$excludeIdsAddress = array_merge($excludeIdsAddress, array_column($mutualFriends, 'user_id'));
				
				$excludeIdsAddress = array_unique($excludeIdsAddress);
			}
			$address_network_users_limit = $mutualFriendslen - 8;
			$address_users = User::where('address_id', Auth::user()->address_id)
				->whereNotIn('user_id', $excludeIdsAddress)
				->limit($address_network_users_limit)
				->get()
				->toArray();
			$users = array_merge( $users ,$address_users);
		}

		$users = UserController::setCuratedFields($users);
		$address = Auth::user()->getAddress;
		$curated_address = '';
		if($address){
			$address = $address->toArray();
			$curated_address = $address['city'].', '.$address['state_name'];
		}
        $data = [
			'inviters' => [],
			'networkmembers' => $users,
			'address' => $curated_address
        ];
        return response()->json( $data );
    }
	public function networkUsers()
	{
		$data['success'] = true;
		$data['data'] = [];
		try {
			$networkMembers = Auth::user()->getNetworkMembers()->toArray();
			$users = [];
			for ($i=0; $i < count($networkMembers); $i++) { 
				$users[] = User::whereUserId($networkMembers[$i]['user2_id'])->first();
			}
			$users = UserController::setCuratedFields($users);
			$data['data']['users'] = $users;
		} catch (\Throwable $th) {
			$data['success'] = false;
		}
		return $data;
	}
	public function instutionsNetwork()
	{
		$data['success'] = true;
		$data['institutions'] = [];
		$currently_working = 0;
		
		$profileExperiences = Auth::user()->profile ?  Auth::user()->profile->profileExperiences->first(): null;
		$institute_id = null;
		if($profileExperiences){
			$institute_id = $profileExperiences['institution_id']; 
		}
		else{
			return response()->json($data);
		}
		// Get institutions followed by me
		$followedInstitution_ids = DB::table('institution_follows')
			->select('institution_id')
			->where('user_id', '=', Auth::user()->user_id)
			->distinct()
			->get()
			->toArray();
		// STD -> JSON
		$followedInstitution_ids = json_decode(json_encode($followedInstitution_ids), true);
		
		// Remove Institution if i am owner of it by including in following Institutions
		$removeInstitutionIfOwner = Auth::user()->getInstitution;
		if($removeInstitutionIfOwner){
			array_push($followedInstitution_ids, ['institution_id' => $removeInstitutionIfOwner->toArray()['institution_id']]);
		}
		// echo('<pre>');print_r($followedInstitution_ids);echo('</pre>');die('call');
		// Get institutions Not followed by me
		$data['institutions'] = Institutions::whereNotIn('institution_id', array_column($followedInstitution_ids, 'institution_id'))->get()->toArray();

		$friends = FriendShips::selectCurrentWorkingFriendsByMyInstitute()->toArray();

		for ($i=0; $i < sizeof($data['institutions']); $i++) { 
			
			// Friends Currently Working 
			if($data['institutions'][$i]['institution_id'] == $institute_id){
				$currently_working = count($friends);
			}
			// Aluminis except me
			$countAluminis = ProfileExperience::select('profile_experience.profile_id')
				->where('institution_id', $data['institutions'][$i]['institution_id'])
				->where('profile_id', '!=', Auth::user()->profile->profile_id)
				->where('profile_experience.is_currently_engaged','=', 0 )
				->distinct()
				->get()
				->toArray();
			$data['institutions'][$i]['aluminis'] = count($countAluminis) > 0 ? count($countAluminis) . ' '. Lang::get("lang.network_recommend_institute_aluminis"): 'ㅤ';
			$data['institutions'][$i]['currently_working'] = $currently_working > 0 ? $currently_working . ' '. Lang::get("lang.network_recommend_institute_connections"): 'ㅤ'; 
		}
		// Set Default Fields
		$data['institutions'] = ControllersNetworkMembersController::setCuratedFields($data['institutions']); 

		return response()->json($data);
	}
	public static function filterMutualFriendsWithOutNetworksRequests($users = [], $friendrequests = null, $mutualFriends = null)
	{	
		if($friendrequests){
			$friendrequests = NetworkMembersController::getRequestsSentReceivedUsers(); 
		}
		if($mutualFriends){
			$mutualFriends = FriendShips::selectMutualFriends()->toArray(); 
		}
		$excludeIds = array_unique($friendrequests);
		for ($i=0; $i < sizeof($mutualFriends); $i++) {
			if(!in_array($mutualFriends[$i]['user_id'], $excludeIds) ){
				$user =	User::find($mutualFriends[$i]['user_id'])->toArray();
				$user['friends'] = [];
				$user['friends']['mutual_friends_count'] = $mutualFriends[$i]['mutuals_count'];
				$users[] = $user;
			} 
		}
		return $users;
	}
	public static function getRequestsSentReceivedUsers()
	{
		// Get Requests sent people or received from
		$friendrequests =	DB::table('friend_requests')
			->where('friend_requests.requester_id', '=', Auth::user()->user_id)
			->orWhere('friend_requests.invited_id', '=', Auth::user()->user_id)
			->select('friend_requests.requester_id', 'friend_requests.invited_id')
			->get()
			->toArray();
			
		return array_merge(array_column($friendrequests, 'requester_id'), array_column($friendrequests, 'invited_id'));
	}
    /**
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function add( $username ) {
        $user = User::whereUsername( $username )->firstOrFail();

        Auth::user()->addToNetwork( $user );
        // $newFriend = Auth::user()->getNetworkMembers()->where('username', $username)->get();
        return back();
    }

    /**
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove( $username ) {
        $user = User::whereUsername( $username )->firstOrFail();

        Auth::user()->removeFromNetwork( $user );

        return back();
    }

	public function institutionsAsk($instituteSlug) {
        $data = [
            'success'   => true,
            'data'      => []
        ];
        $institute = Institutions::where('slug', $instituteSlug)->first();
        if ($institute) {
            $follows = DB::table('institution_follows')
			->where('user_id', Auth::user()->user_id)
			->where('institution_id', $institute['institution_id'])
			->first();
            if(!$follows){
                DB::table('institution_follows')->insert(
                    ['institution_id' => $institute['institution_id'], 'user_id' => Auth::user()->user_id]
                );
				User::whereUserId($institute['owner_id'])
				->first()
				->notify( new GotFollowForInstitution( Auth::user() ) );
                
				return response()->json($data);
            }
        }
        $data['success'] = false;
        $data['data'] = [];
        return response()->json($data);
    }
	public static function userInFriendOrRequests($userId)
	{
		$isFriend = FriendShips::where('friend2_id', $userId)
		->where('friend1_id', Auth::user()->user_id)
		->get();
		
		// is not a friend
		$data['type'] 	= 'None';
		$data['title'] 	= 'Connect';

		if($isFriend > 0){
			// is a friend
			$data['type'] 	= 'Friend';
			$data['title'] 	= 'Friend';
		}else{
			$inRequestSent = DB::table('friend_requests')->where('requester_id', Auth::user()->user_id)
			->where('invited_id', $userId)
			->get();
			if($inRequestSent > 0) {
				// I had sent request 
				$data['type'] = 'RequestSent';
			}else{
				$inRequestReceived = DB::table('friend_requests')->where('requester_id',$userId)
				->where('invited_id', Auth::user()->user_id)
				->get();
				if($inRequestReceived > 0) {
					// He has sent me request
					$data['type'] = 'RequestReceived';
				}
			}
		}
		return $data;
	}

	public static function setCuratedFields($institutions)
	{
		for ($i=0; $i < sizeof($institutions); $i++) { 
			
			if(!isset($institutions[$i]['institution_photo'])) {
				$institutions[$i]['institution_photo'] = '/images/Institutions/cover_images/default_uni_profile.jpg';
			}
			if(!isset($institutions[$i]['institution_cover'])) {
				$institutions[$i]['institution_cover'] = '/images/Profile/cover_image/default_cover.png';
			}
		}
		return $institutions;
	}

	public function institutionsFollowToggle($instituteId=null)
	{	
		$data['success'] = false;
		$data['data'] = [];
		if($instituteId){
			try {
				$record = InstitutionFollows::where('institution_id', '=', $instituteId)
					->where('user_id', '=', Auth::user()->user_id)
					->get();
				if(count($record) > 0) {
					InstitutionFollows::where('institution_id',$instituteId)
						->where('user_id', '=', Auth::user()->user_id)
						->delete();
				}else{
					InstitutionFollows::insert(
					[
					'institution_id' => $instituteId, 
					'user_id' => Auth::user()->user_id
					]);
				}
				$data['success'] = true;
			} catch (\Throwable $th) {
				$data['success'] = false;
			}
		}
		return $data;
	}
}