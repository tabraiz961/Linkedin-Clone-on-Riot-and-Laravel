<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController as ControllersUserController;
// use App\Networks;
use App\FriendShips;
use App\Addresses;
use App\Http\Helpers\HelpersFunctions;
use App\Institutions;
use App\Post;
use App\ProfileDetails;
use App\ProfileExperience;
use App\ProfileSkill;
use App\ProfileSkillAppreciation;
use App\Skills;
use App\User;
use App\Utils;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;

class UserController extends Controller
{
	public function list() {
		$users = User::paginate( 20 );
		UserController::setCuratedFields($users);
		return view( 'app.user.list', [
			'users' => $users
		]);
	}

	public function timeline(Request $request, $post_id = null) {
		$searchKeyword = '';
		if(isset($request[0]) && $request[0] !=''){
			$searchKeyword = trim($request[0]);
		}
		$posts = Auth::user()->selectorTimeline($post_id, $searchKeyword);
		// $posts = $posts->get();
		foreach ($posts as  $value) {
			if ($searchKeyword != '') {
				$value->description = substr_replace($value->description, '<strong>'.$searchKeyword.'</strong>', stripos($value->description, $searchKeyword), strlen($searchKeyword));
			}
			$value->post_type = self::getPostType($value);
			if($value->post_type == "FILE"){
				$temp  = explode('/',$value->image_url);
				$value->curated_file_name = ucfirst($temp[count($temp) - 1]);
			}
			$value->created_at_curated = \Carbon\Carbon::parse($value->created_at)->diffForhumans();
			$value->canEditPost = false;
			if($value->author_id == Auth::user()->user_id){
				$value->canEditPost = true;
			}
		}
		$posts = json_decode(json_encode($posts),true);
		$posts = ControllersUserController::setCuratedFields($posts);
		return response()->json( $posts );
	}

	public function getPostType($post)
	{	
		$ext = '';
		if($post->image_url && $post->image_url!=''){
			$arr = explode('.',$post->image_url);
			$ext = $arr[count($arr) - 1];
		}else{
			return '';
		}
		$type = '';
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'jfif':
				$type = 'IMAGE';
				break;
				
			case 'mp4':
			case 'mov':
			case 'mkv':
			case 'flv':
				$type = 'VIDEO';
				break;
		
			case 'pdf':
			case 'doc':
			case 'xlsx':
			case 'csv':
				$type = 'FILE';
				break;
			default:
				# code...
				break;
		}
		return $type;
	}
	public function images( $after = null ) {
		return $this->imagesUser( Auth::user()->username, $after );
	}

	public function imagesUser( $username, $after = null ) {
		$user = User::whereUsername( $username )->firstOrFail();

		$posts = $user->selectorImages()->limit( 20 );
		if( $after != null ) {
			$posts->where( 'post_id', '<', $after );
		}
		$posts = $posts->get();

		return response()->json( $posts );
	}

	public function videos( $after = null ) {
		return $this->videosUser( Auth::user()->username, $after );
	}

	public function videosUser( $username, $after = null ) {
		$user = User::whereUsername( $username )->firstOrFail();

		$posts = $user->selectorVideos()->limit( 200 );
		if( $after != null ) {
			$posts->where( 'post_id', '<', $after );
		}
		$posts = $posts->get();

		return response()->json( $posts );
	}

	public function events( $after = null ) {
		return $this->eventsUser( Auth::user()->username, $after );
	}

	public function eventsUser( $username, $after = null ) {
		$user = User::whereUsername( $username )->firstOrFail();

		$posts = $user->selectorEvents()->limit( 20 );
		if( $after != null ) {
			$posts->where( 'event_id', '<', $after );
		}
		$posts = $posts->get();

		return response()->json( $posts );
	}

	public function profile( $username ) {
		$user['network_members'] = [];
		// Fail and redirect to 404
		$user = User::whereUsername( $username )->firstOrFail();
		$user['isSame'] = 0;
		if(Auth::check()){
			$user['isSame'] = Auth::user()->isSame( $user );
		}
		// $networkUsers = $user->getNetworkMembers()->toArray();
		// if(count($networkUsers)) {
			// 	$user['network_members'] = ControllersUserController::setCuratedFields($networkUsers)[0];
		// }
		$user = ControllersUserController::setCuratedFields([$user])[0];
		$user['job_experience'] = self::getExperienceProfileByType($user, 1);
		$user['education_experience'] = self::getExperienceProfileByType($user, 2);
		$user['profile_skills'] = self::profileSkills($user);
		$user = $user->toArray();
			
		$temp = ProfileDetails::where('user_id', $user['user_id'])->first();
		$user['profile_details'] = isset($temp)  ? $temp->toArray():[];
		$user = self::handleProfileEmptyStrings($user);
		$user['user_type'] = HelpersFunctions::getUserType();
		// $user['friend_status'] = NetworkMembersController::userInFriendOrRequests($user['user_id']);
		// echo('<pre>');print_r($user);echo('</pre>');die('call');
		return view( 'app.user.profile', [
			'user' => $user,
		]);
	}
	public function profileSkills($user)
	{
		$skills = $this->getProfileSkills($user);
		return $this->sortedSkillsforProfile($skills); 
	}
	public function sortedSkillsforProfile($skills)
	{
		$otherIncrementor = 0;
		if(!count($skills)){
			return [];
			
		}
		usort($skills, function ($a, $b) use ($skills) {
			return count($b['appreciators']) - count($a['appreciators']);
		});
		
		$data = [];
		if(count($skills) <4 ){
			$data['others'] = $skills;
		}else{
			$data['top_appreciated'] = [];
			// For Top Apprciated
			// If first is greater than last in users count
			if ( count($skills[0]['appreciators']) >  count($skills[count($skills)-1]['appreciators']) ) {
				$data['top_appreciated'] = [$skills[0]];
				$otherIncrementor++;
			}
			// For Followed
			$appreciators = array_filter(array_slice($skills, $otherIncrementor),  function($var) {
				return count($var['appreciators']) > 0;
			});

			$sliceCount  = count($appreciators) > 3 ? 3 : count($appreciators);
			$followed = array_slice($skills,$otherIncrementor, $sliceCount ); //1-3 
			$data['followed'] = $followed;
			$otherIncrementor+=$sliceCount;
			
			// For Others
			$remainingSkills = array_slice($skills, $otherIncrementor);
			$data['others'] = count($remainingSkills) > 0 ? $remainingSkills :[];
			
			// Set is_owner for Highlighting Viewers appreciated Skills 
			$data['top_appreciated'] = $this->userIsAppreciateOwner($data['top_appreciated']);
			$data['followed'] = $this->userIsAppreciateOwner($data['followed']);
		}
		$data['others'] = $this->userIsAppreciateOwner($data['others']);
		return $data;
	}
	public function userIsAppreciateOwner($data)
	{
		if(Auth::check()){
			for ($i=0; $i < count($data); $i++) { 
				if(count($data[$i]['appreciators']) > 0){
					for ($j=0; $j < count($data[$i]['appreciators']); $j++) { 
						if($data[$i]['appreciators'][$j]['user_id'] == Auth::user()->user_id){
							$data[$i]['is_owner'] = true;
						}
					}
				}
			}
		}
		return $data;
	}

	public function getProfileSkills($user = null)
	{
		$skills = [];
		$user = isset($user) ? $user : Auth::user();
		if($user->profile){
			$skills = ProfileSkill::where('profile_id', '=', $user->profile->profile_id)
			->get();
			foreach ($skills as $key => $value) {
				$value->skillName;
				$value->appreciators;
			}
			$skills = $skills->toArray();
		}
		return $skills;
	}
	public function appreciated(Request $request, $username, $skillId)
	{
		// block owner from sending personal requests
		$isDeleted = false;
		if(isset($skillId) && is_numeric($skillId)  ){
			$data = [];
			$data['success'] = true;
			$data['data'] = [];
			try {
				$skill = Skills::where('id','=', $skillId)->first();
				$user = User::where('username','=', $username)->first();
				if(isset($skill) && isset($user) && $user->profile && $user->profile->profileSkills) {
					foreach ($user->profile->profileSkills as $key => $value) {
						foreach ($value->appreciators as $key2 => $value2) {
							if($value2->SkillOwner->skill_id == $skillId && Auth::user()->user_id == $value2->user_id){
								// echo('<pre>');print_r(Auth::user()->user_id);echo('</pre>');
								// echo('<pre>');print_r($value2->toArray());echo('</pre>');
								// echo('<pre>');print_r($skillId);echo('</pre>');die('call');
								// if Appreciation exists Delete it
								ProfileSkillAppreciation::where('profile_skill_id', '=', $value2->profile_skill_id)
								->where('user_id', '=', Auth::user()->user_id )->delete();							
								$isDeleted = true;
								break (2);
							}
						}
					}
					if(!$isDeleted){
						// die('call2');
						// if not deleted then add it
						$profileSkill = ProfileSkill::where('profile_id', '=',$user->profile->profile_id )
						->where('skill_id', '=',$skillId)
						->first();
						$ProfileSkillAppreciationId = ProfileSkillAppreciation::insertGetId(['profile_skill_id'=> $profileSkill->id, 'user_id' => Auth::user()->user_id ]);
					}
					$data['data']['profile_skills'] = $this->profileSkills($user);
						return response()->json($data);
				}
			} catch (\Throwable $th) {
				$data['success'] = false;
				$data['msg'] = $th->getMessage();
				return response()->json($data);
			}
		}
	}
	public function handleProfileEmptyStrings($user)
	{
		$user['profile_details'] = Utils::handleEmptyStringKeys($user['profile_details']);
		$user = Utils::handleEmptyStringKeys($user);
		return $user;
	}
	public function getExperienceProfileByType($user, $type) {
		$jobExperience = [];

		if( isset($user->profile) && isset($user->profile->profileExperiences)){
			$jobExperience = $user->profile->profileExperiences->where('experience_type_id', $type)->toArray(); 
			$jobExperience = HelpersFunctions::_group_by($jobExperience, 'institution_id');
			$temp = [];
			foreach ($jobExperience as $key => $value) {
				$obj = [];
				$obj['institute'] = Institutions::where('institution_id', $key)->first()->toArray();
				$obj['experiences'] = $value;
				$temp[]	= $obj;
			}

			$jobExperience = $temp;
		}
		else{
			return [];
		}
		// Sort By End Time
		foreach ($jobExperience as $key => $value) {
			if(isset($value['institute'])){
				array_multisort(
					array_map('strtotime',array_column($jobExperience[$key]['experiences'], 'end')),
					SORT_DESC,
					$jobExperience[$key]['experiences']
				);
				// $jobExperience[$key]['institute'] = InstitutionController::getCuratedInstitution($jobExperience[$key]['institute']);
			}
			// If last item has Null, pop and unshift to move it to first
			if(count($jobExperience[$key]['experiences']) > 1 && $jobExperience[$key]['experiences'][count($jobExperience[$key]['experiences']) -1]['end'] == null){
				$nulled_endtime =	array_pop($jobExperience[$key]['experiences']);
				array_unshift($jobExperience[$key]['experiences'],$nulled_endtime);
			}
		}
		foreach ($jobExperience as $key => $value) {
			$temp = '';
			$start = null;
			$end = null;
			$isWorking = '';
			foreach (array_reverse($value['experiences']) as $key2 => $value2) {
				$start = isset($start)  ? $start : new DateTime($value2['start']);
				if(isset($value2['end'])){
					$end = new DateTime($value2['end']);
				}else{
					$end = new DateTime();
					$isWorking = ' - Currently Working';
				}
				$address = Addresses::where('id', $value2['address_id'])->first();
				if(isset($address)){
					$address  = $address->toArray();
					$jobExperience[$key]['experiences'][$key2]['address_custom'] = $address['city'].', '.$address['state_name'];  
				}else{
					$jobExperience[$key]['experiences'][$key2]['address_custom'] = 'ㅤ';
					
				}
			}
			$interval = $start->diff($end);
			$duration_string = isset($interval->y) && $interval->y > 0 ?  $interval->y . ' Yrs, ':  '';
			$duration_string .= isset($interval->m) && $interval->m > 0 ?  $interval->m . ' mnths':  '';
			$duration_string .= $isWorking;
			$jobExperience[$key]['institute']['duration'] = $duration_string; 
			$jobExperience[$key]['institute'] = InstitutionController::getCuratedInstitution($jobExperience[$key]['institute']);
		}
		// echo('<pre>');print_r($jobExperience);echo('</pre>');die('call');
		return $jobExperience;
	}
	public function skillUpdate(Request $request)
	{
		if(Auth::check()){
			$data['success'] = true;
			$data['data'] = [];
			$skillsIds = $request->get('skills');
			$profileSkills = ProfileSkill::where('profile_id', '=', Auth::user()->profile->profile_id)
				->whereNotIn('skill_id',$skillsIds)
				->select('id')
				->get();
				
			if(count($profileSkills->toArray())){
				// Delete Appreciations
				ProfileSkillAppreciation::whereIn('profile_skill_id',array_column($profileSkills->toArray(), 'id'))->delete();
			}
			
			// Delete Skills 
			ProfileSkill::where('profile_id', '=', Auth::user()->profile->profile_id)
			->whereNotIn('skill_id',$skillsIds)->delete();
				
			// Add Skills 
			$oldSkills = ProfileSkill::where('profile_id', '=', Auth::user()->profile->profile_id )
			->select('skill_id')
			->get()
			->toArray();
				

			$oldSkillsIds = array_column($oldSkills, 'skill_id');
			$insertIds = array_diff($skillsIds, $oldSkillsIds);
			foreach ($insertIds as  $value) {
				ProfileSkill::insert(['skill_id'=> $value, 'profile_id' => Auth::user()->profile->profile_id]);
			}

			$data['data']['profile_skills'] = $this->profileSkills(Auth::user());
			return response()->json($data);
		}
		return [];
	}
	public function network() {  
		// $friends = FriendShips::selectFriendsAndRequested()->toArray(); 
		// $users = [];
		// $excludeIds = array_column($friends, 'user_id'); 
		
		// // Exclude myself
		// $excludeIds[] = Auth::user()->user_id;
		
		// $friends_len = sizeof($friends);
		// for ($i=0; $i < $friends_len; $i++) { 
		// 	$user =	User::find($friends[$i]['user_id'])->toArray();
		// 	$user['friends'] = [];
		// 	$user['friends']['mutual_friends_count'] = $friends[$i]['mutuals_count'];
		// 	$users[] = $user;
		// }
		// if($friends_len < 8 ){
		// 	$myfriends = FriendShips::where( 'friendships.friend1_id', '=', Auth::user()->user_id )->get(['friend2_id'])->toArray();
		// 	if(count($myfriends)){
		// 		// Exclude my friend ids 
		// 		$excludeIds[] = array_column($myfriends, 'friend2_id');
		// 	}
		// 	// Include same address People
		// 	$address_network_users_limit = $friends_len - 8;
		// 	$address_users = User::where('address_id', Auth::user()->address_id)
		// 		->whereNotIn('user_id', $excludeIds)
		// 		->limit($address_network_users_limit)
		// 		->get()
		// 		->toArray();
		// 	$users = array_merge( $users ,$address_users);
		// }
		// $users = UserController::setCuratedFields($users);
		// $address = Auth::user()->getAddress->toArray();
		$user = ControllersUserController::setCuratedFields([Auth::user()->toArray()])[0];
		return view( 'app.networks.list', [
			'inviters' => [],
			'networkmembers' => [], //$users,
			'address' => '', //$address['city'].', '.$address['state_name']
			'user' => $user
		]);
	}

	public function update(Request $request, $username){

		$user = User::whereUsername( $username )->firstOrFail();
		$params = $request->all();
		if( isset( $params[ 'email' ] ) && $params[ 'email' ] === $user->email ) {
			unset( $params[ 'email' ] );
		}
		$params['profile_path'] = '';
		$params['cover_path'] = '';
	    $validator = Validator::make($params, User::validation_update);
	    if($validator->fails()) {
		    return redirect()->back()->withErrors($validator)->withInput();
	    }

		if( $request->has('name')) {
			$user->name = $request->input('name');
		}
		if( $request->has('surname')) {
			$user->surname = $request->input('surname');
		}
		if( $request->has('username')) {
			$user->username = $request->input('username') ;
		}
		// if( $request->has('email')) {
		// 	$user->email = $request->input('email') ;
		// }
		// if( $request->has('birth_date')) {
		// 	$user->birth_date = $request->input('birth_date') ;
		// }
		// if( $request->has('password') ) {
			// 	$user->setPassword( $request->input('password') );
		// }
		
		if( $request->hasFile('cv') ) {
			$cv_path = Utils::store( $user, $request->file( 'cv' ), 'images/cv/' );
			$user->cv_url =  \App\Utils::getFileUrl( $cv_path );
		}
		if( $request->has('photo_path') && $request['photo_path'] != null ) {
			$profile_path = Utils::store( $user, $request->file( 'photo_path' ), 'images/profile_picture/' );
			$profile_path = \App\Utils::getFileUrl( $profile_path );
			$user->photo_path = $profile_path;
		}
		
		if( $request->has('cover_path') && $request['cover_path'] != null ) {
			$cover_path = Utils::store( $user, $request->file( 'cover_path' ), 'images/cover_picture/' );
			$cover_path = \App\Utils::getFileUrl( $cover_path );
			$user->cover_path = $cover_path;
		}
		
		if( $request->has('title')) {
			$user->title = $request->input('title');
		}
		$user->save();
		if( $request['profile_details']) {
			$short = '';
			$long = '';
			$updates = [];
			if( $request['profile_details']['short_bio']) {
				$updates['short_bio'] = $request['profile_details']['short_bio'];
			}
			if( $request['profile_details']['long_description']) {
				$updates['long_description'] = $request['profile_details']['long_description'];
			}
			ProfileDetails::where('user_id', Auth::user()->user_id)
			->update($updates);
		}
		if($request->get('is_ajax'))
		{
			$data['success'] = true;
			$data['message'] = '';
			$data['data']['cv'] = $user->cv_url;
			return response()->json( $data );
		}
		return redirect()->route( 'user.profile', [ 'username' => $user->username ] );
	}

	public function education( Request $request, $username ) {
		$user = User::whereUsername( $username )->firstOrFail();

		if( !Auth::user()->role == 'ADMIN' && !Auth::user()->isSame( $user ) ) {
			return response()->json( [ 'errors' => 'Unauthorized' ], 401 );
		}

		$validator = Validator::make( $request->all(), [
			'data' => 'required|array'
		]);

		if( $validator->fails() ) {
			return response()->json( [ 'errors' => $validator->errors() ], 420 );
		}

		if( $user->infos == null ) {
			$user->infos = '{}';
		}
		$infos = json_decode( $user->infos, true );
		$infos[ 'education' ] = $request->get( 'data' );
		$user->infos = json_encode( $infos );
		$user->save();

		return response()->json();
	}
	public function experienceNetwork(Request $request)
	{
		$data = [];
		
		// Get My Institution
		$experiences = 		$profileExperiences = Auth::user()->profile ?  Auth::user()->profile->profileExperiences->toArray(): [];

		$sortedJobAndInstitutions = self::getSortedJobAndInstitutions($experiences);
		if(count($sortedJobAndInstitutions) > 0){

			$institution = Institutions::where('institution_id',array_key_first($sortedJobAndInstitutions))->get()->toArray();
			
			// Get My Institution Users 
			$institution_users = ProfileExperience::join('profile_details', 'profile_experience.profile_id', '=', 'profile_details.profile_id')
				->select('profile_details.user_id')
				->where('institution_id', $institution[0]['institution_id'])
				->where('user_id', '!=', Auth::user()->user_id)
				->distinct()
				->get()
				->toArray();
			$institution_users_ids = array_column($institution_users, 'user_id');
			$friendrequests = NetworkMembersController::getRequestsSentReceivedUsers();
			$uniqueFriendRequests = array_unique($friendrequests);
			
			$friends = array_column(FriendShips::selectFriends()->toArray(),'friend2_id');
			// Filter institute users without FriendRequest Sent and receved and Not Friends
			$filteredInstituteUsers = [];
			foreach ($institution_users_ids as $key => $value) {
				if( !in_array($value, $uniqueFriendRequests) && !in_array($value, $friends) ){
					$filteredInstituteUsers[] = $value;
				}
			}
			
			$finalUsers = [];
			foreach ($filteredInstituteUsers as $key => $value) {
				$finalUsers[] = User::where('user_id',$value)->first();
			}
			$finalUsers = UserController::setCuratedFields($finalUsers);
	
			$data['institution'] 	= $institution[0];
			$data['users'] 		 	= $finalUsers;
			
			// Get MutualFriendsWithOutNetworksRequests
	
			// $mutualFriends = FriendShips::selectMutualFriends()->toArray();
			// $users = NetworkMembersController::filterMutualFriendsWithOutNetworksRequests([], $friendrequests, $mutualFriends);
			
	
			if (count($institution) || count($data['users'])  ) {
				return response()->json( [ 'success' => 'true', 'data' => $data ] );
			}
		}

		return response()->json( [ 'success' => 'false', 'data' => [] ] );
	}

	public function getSortedJobAndInstitutions($experiences)
	{
		if(count($experiences)){
			// Get institution id 
			$institutions_arr =  array_unique(array_column($experiences, 'institution_id'));
			// Convert to Object
			$sortedInstitutionsKeys = array_flip($institutions_arr);
			// Initialize to Array 
			$sortedInstitutionsKeys = array_fill_keys(array_keys($sortedInstitutionsKeys), []);
		}
		
		// Group By Institutions
		$sortedInstitutions = [];
		for ($i=0; $i < count($experiences); $i++) { 
			$sortedInstitutions[$experiences[$i]['institution_id']][] = $experiences[$i];
		}
		// Sort By Jobs parent or Promotions in Instittions
		$sortedJobAndInstitutions = [];
		foreach ($sortedInstitutions as $key => $value) {
			$value = array_reverse($value, true);
			$experience_parent_id_arr = array_column($value, 'experience_parent_id');
			array_multisort($experience_parent_id_arr, SORT_ASC, $value);
			$sortedJobAndInstitutions[$key] = $value;
		}
		return $sortedJobAndInstitutions;
	}
	public function experience( Request $request, $username ) {
		$user = User::whereUsername( $username )->firstOrFail();

		if( !Auth::user()->role == 'ADMIN' && !Auth::user()->isSame( $user ) ) {
			return response()->json( [ 'errors' => 'Unauthorized' ], 401 );
		}

		$validator = Validator::make( $request->all(), [
			'data' => 'required|array'
		]);

		if( $validator->fails() ) {
			return response()->json( [ 'errors' => $validator->errors() ], 420 );
		}
		$response['success'] = true;
		
		$data = $request->get( 'data' );
		$arr = array(
			'institution_id' => $data['institution_id'],
			'is_currently_engaged' => $data['is_currently_engaged'],
			'experience_type_id' => $data['experience_type_id'],
			'is_currently_engaged' => $data['is_currently_engaged'],
			'address_id' => $data['address_id'],
			'experience_title' => $data['experience_title'],
			'description' => $data['description'],
			'start' => $data['start'],
			'end' => $data['end'],
			'experience_parent_id' => 0,
		);
		$experienceId = 0;
		if(isset($data['experience_id'])){//update
			ProfileExperience::where('experience_id', $data['experience_id'])->update($arr);
			$experienceId =  $data['experience_id'];
		}else{ //insert
			$arr['profile_id'] = Auth::user()->profile->profile_id;
			$arr['priority_id'] = 0;
			$experienceId = ProfileExperience::insertGetId($arr);
		}
		$experience_type = ProfileExperience::where('experience_id', $experienceId)->firstOrFail()['experience_type_id'];
		$response['success'] = true;
		// echo('<pre>');print_r();echo('</pre>');die('call');
		// if( $user->infos == null ) {
		// 	$user->infos = '{}';
		// }
		// $infos = json_decode( $user->infos, true );
		// $infos[ 'experience' ] = $request->get( 'data' );
		// $user->infos = json_encode( $infos );
		// $user->save();
		// $response['data']['institute'] = ProfileExperience::where('experience_id', $data['experience_id'])->firstOrFail();
		// $response['data']['institute']['experience_id'] = $data['experience_id'];
		$response['data'] = self::getExperienceProfileByType(Auth::user(), $experience_type);

		return response()->json($response);
	}
	public function removeInstitute($instituteId, Request $request)
	{
		// echo('<pre>');print_r($request->get('experience_type_id'));echo('</pre>');die('call');
		$data['success'] 	= false;
		$data['data']		= [];
		$experiences = ProfileExperience::where('institution_id', $instituteId)
		->where('profile_id', Auth::user()->profile->profile_id)
		->where('experience_type_id', $request->get('experience_type_id'))
		->delete();
		if($experiences){
			$data['success'] = true;
		}
		return response()->json($data);

		// echo('<pre>');print_r($experiences->toArray());echo('</pre>');die('call');
	}
	public function removeExperience($experienceId, Request $request)
	{
		$data['success'] 	= false;
		$data['data']		= [];
		$experiences = ProfileExperience::where('experience_id', $experienceId)
		->where('profile_id', Auth::user()->profile->profile_id)
		->where('experience_type_id', $request->get('experience_type_id'))
		->delete();
		if($experiences){
			$data['success'] = true;
		}
		return response()->json($data);
	}
	public function skill( Request $request, $username ) {
		$user = User::whereUsername( $username )->firstOrFail();

		if( !Auth::user()->role == 'ADMIN' && !Auth::user()->isSame( $user ) ) {
			return response()->json( [ 'errors' => 'Unauthorized' ], 401 );
		}

		$validator = Validator::make( $request->all(), [
			'data' => 'required|array'
		]);

		if( $validator->fails() ) {
			return response()->json( [ 'errors' => $validator->errors() ], 420 );
		}

		if( $user->infos == null ) {
			$user->infos = '{}';
		}
		$infos = json_decode( $user->infos, true );
		$infos[ 'skill' ] = $request->get( 'data' );
		$user->infos = json_encode( $infos );
		$user->save();

		return response()->json();
	}


	public function newprofiles( $after = null ) {
		// Gettting list  
		$exclude_list = DB::table('friend_requests')
					->select('*')
					// of already connect request sent
					->where('friend_requests.requester_id', '=', Auth::user()->user_id)
					// of recieved connect request 
					->orWhere('friend_requests.invited_id', '=', Auth::user()->user_id)
					->get();
		$exclude_list_array = array_map(function($item) {
			$temp = (array)$item;
			return $temp['invited_id']; 
		}, $exclude_list->toArray());
		$exclude_list_array2 = array_map(function($item) {
			$temp = (array)$item;
			return $temp['requester_id']; 
		}, $exclude_list->toArray());
		
		// Exclude Friends
		$exclude_friend_list = [];
		$exclude_friend_query = Auth::user()->getFriends()->toArray();
		if(count($exclude_friend_query)>0){
			$exclude_friend_list = array_column($exclude_friend_query, 'user_id');
		}
		// Merge all with not me included
		$exclude_list_all = array_merge($exclude_list_array,$exclude_list_array2,$exclude_friend_list,[Auth::user()->user_id]);
		$exclude_list_unique = array_unique($exclude_list_all);

		$users = DB::table('users')                 
			->whereNotIn('user_id', $exclude_list_unique)
			->limit(5)
			->get();
		
		$users = json_decode(json_encode($users),true);
		$users = UserController::setCuratedFields($users);

		return response()->json( $users );
	}

	public function delete( $username ) {
		$user = User::whereUsername( $username )->firstOrFail();

		if( !Auth::user()->role == 'ADMIN' && !Auth::user()->isSame( $user ) ) {
			return response()->json( [ 'errors' => 'Unauthorized' ], 401 );
		}

		$user->delete();

		return redirect()->route( 'index' );
	}
	public static function setCuratedFields($users)
	{
		$titleData = self::getProfileGeneratedTitles($users);
		$idsOfGeneratedTitle = array_column($titleData, 'user_id');

		for ($i=0; $i < sizeof($users); $i++) { 
			$users[$i]['title_curated'] = HelpersFunctions::BLANK_CHARACTER;
			if(isset($users[$i]['name']) && isset($users[$i]['surname'])){
				$users[$i]['curated_name'] = ucfirst($users[$i]['name']) . " " . ucfirst($users[$i]['surname']);
			}
			if(isset($users[$i]['photo_path'])){
				$users[$i]['photo_id'] = $users[$i]['photo_path'];
			}
			if(!isset($users[$i]['photo_id'])){
				if(isset($users[$i]['gender'])){
					$users[$i]['photo_id'] = $users[$i]['gender'] == 'male' ? asset('images/male_default.png'): asset('images/female_default.png'); 
				}else{
					$users[$i]['photo_id'] = asset('images/male_default.png');
				}
			}
			
			if(!isset($users[$i]['cover_path'])) {
				$users[$i]['cover_path'] = '/images/Profile/cover_image/default_cover.png';
			}

			if(isset($users[$i]['user_id']) && in_array($users[$i]['user_id'], $idsOfGeneratedTitle) ) {
				for ($j=0; $j < count($titleData); $j++) { 
					if($users[$i]['user_id'] == $titleData[$j]['user_id']){
						$users[$i]['title_curated'] = $titleData[$j]['title'] ? $titleData[$j]['title'] : ( ($titleData[$j]['experience_title'] && $titleData[$j]['institution_name']) ? $titleData[$j]['experience_title'] .' - '. $titleData[$j]['institution_name'] : HelpersFunctions::BLANK_CHARACTER);
 					}
				}
			}
		}
		// echo('<pre>');print_r($users[$i]['cover_path']);echo('</pre>');die('call');
		return $users;
	}
	public static function getProfileGeneratedTitles($users = [])
	{	$titleData = [];
		if( count($users) > 0){
			$userIds = array_column($users,'user_id');

			//Get public title for users and also get their last/current working job(1) 
			$titleData = User::leftJoin('profile_details', 'users.user_id', '=', 'profile_details.user_id')
			->leftJoin('profile_experience', 'profile_details.profile_id', '=', 'profile_experience.profile_id')
			->leftJoin('institution', 'institution.institution_id', '=', 'profile_experience.institution_id')
			->select('users.user_id','users.title', 'profile_experience.experience_title','institution_name' )
			->whereIn('users.user_id', $userIds)
			->where(function ($query) {
				$query->where('profile_experience.is_currently_engaged', '=', 1)
				->orWhere(function ($query2) {
					$query2->orderBy('profile_experience.end', 'desc')
					->limit(1);
				})
				->orWhereRaw('users.title is not null');
			})
			->where('profile_experience.experience_type_id','=', 1 )
			->get()->toArray();
		}
		return $titleData;

	}
	
	public function imageCuratedUrl($users)
	{
	}
}