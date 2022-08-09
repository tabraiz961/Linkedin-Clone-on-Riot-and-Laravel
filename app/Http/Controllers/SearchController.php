<?php

namespace App\Http\Controllers;

use App\Addresses;
use App\FriendRequests;
use App\Institutions;
use App\Languages;
use App\FriendShips;
use App\InstitutionFollows;
use App\Post;
use App\SearchHistory;
use App\Skills;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller {
	public function users( Request $request ) {
		return view( 'app.search.user', [ 'query' => $request->get( 'q' ) ] );
	}
	public function searchHistory()
	{
		$data['success'] = true;
		$temp = SearchHistory::where('user_id', Auth::user()->user_id)
		->orderBy('id', 'desc')
		->limit(5)
		->get()
		->toArray();
		$data['data'] = array_map(function($item)
		{

			$item['hashtag'] = $item['search_key'];
			unset($item['search_key']);
			return $item;
		},$temp);
		
		return response()->json($data);
	}

	public function jobs( Request $request ) {
		return view( 'app.search.job', [ 'query' => $request->get( 'q' ) ] );
	}
	public function getAddress(Request $request)
	{
		$term = $request->query('term');
		$data = [];
		if($term == ''){
			$data['results'] = Addresses::select(	'id',
				DB::raw('CONCAT(state_name,", ",city) as text'))
				->limit(10)
				->get()
				->toArray();
		}else{
			$data['results'] = Addresses::select(
				'id',
				DB::raw('CONCAT(state_name,", ",city) as text'))
				->where('state_name', 'like', '%'.$term.'%')
				->orWhere('city', 'like', '%'.$term.'%')
				->limit(10)
				->get()
				->toArray();
		}
		$data['pagination']['more'] = false;
		return response()->json($data); 
	}
	public function getSkills(Request $request)
	{
		$term = $request->query('term');
		$data = [];
		if($term == ''){
			$data['results'] = Skills::select(	'id','skill_name AS text')
				->limit(10)
				->get()
				->toArray();
		}else{
			$data['results'] = Skills::select('id','skill_name AS text')
				->where('skill_name', 'like', '%'.$term.'%')
				->limit(10)
				->get()
				->toArray();
		}
		$data['pagination']['more'] = false;
		return response()->json($data); 
	}
	public function getLanguages(Request $request)
	{
		$term = $request->query('term');
		$data = [];
		if($term == ''){
			$data['results'] = Languages::select(	'id','Name as text')
				->limit(10)
				->get()
				->toArray();
		}else{
			$data['results'] = Languages::select('id','Name as text' )
				->where('Name', 'like', '%'.$term.'%')
				->limit(10)
				->get()
				->toArray();
		}
		$data['pagination']['more'] = false;
		return response()->json($data); 
	}

	public static function mainHeaderSearch(Request $request)
	{
		$input = $request->input;
		$inputQuery = '%'.$input.'%';
		// hashtag search
			// timeline posts

		// without hashtag
			// Profiles 
			// Institutions
			// timeline posts
		$users = User::where('user_id', '!=', Auth::user()->user_id)
					->where(function ($query) use ($inputQuery) {
						$query->orWhere('surname', 'like', $inputQuery )
						->orWhere('username', 'like', $inputQuery )
						->orWhere('name', 'like', $inputQuery);
					})
					->get();
		foreach ($users as $value) {
			if($value->profile){
				$value->profile->profileExperiences;
			} 
			// is Friend
			$value->isFriend = Auth::user()->isFriend($value) ? 1 : 0; 
			
			$value->type = 'user';
			
			$value->details =  UserController::setCuratedFields([$value->toArray()])[0];
			
			// Mutual Count 
			$userFriendsArr = 	FriendShips::selectFriends($value->user_id)->toArray();
			$userFriendsIds = array_column($userFriendsArr, 'friend2_id');
			$myFriendsArr = 	FriendShips::selectFriends()->toArray();
			$myFriendsIds = array_column($myFriendsArr, 'friend2_id');
			$mutuals = array_intersect($userFriendsIds, $myFriendsIds);
			$value->mutuals = count($mutuals);

			// Is Request Send
			$value->isRequested = FriendRequests::where('requester_id', Auth::user()->user_id)
				->where('invited_id', $value->user_id)
				->count() > 0 ? 1 : 0;
		}
		$users = $users->toArray(); 
		usort($users, function($a, $b){
			return $b['isFriend'] <=> $a['isFriend'];
		});
		
		$institutions = Institutions::where('institution_name', 'like', $inputQuery)
		->get();
		foreach ($institutions as $value) {
			$value->address;
			$value->typee;
			$value->is_following = count(InstitutionFollows::userFollowing($value->institution_id)->toArray()) > 0 ? 1 : 0;
		}
		$institutions = array_map(function ($item){
			$item['type'] = 'institutions'; 
			return InstitutionController::getCuratedInstitution($item);
		}, $institutions->toArray());

		$posts = Post::where('description', 'like', $inputQuery)
		->get();
		$posts = array_map(function ($item){$item['type'] = 'posts'; return $item;}, $posts->toArray());


		// SearchHistory Maintain
		if($request->saveSearch){
			$search = SearchHistory::where('user_id', Auth::user()->user_id)
			->where('search_key', $request->input)
			->get();
			if(count($search) == 0){
				SearchHistory::insert(['user_id'=> Auth::user()->user_id, 'search_key' => $request->input]);
			}
		}
		$data['data'] = array_merge($users, $institutions, $posts);
		$data['sucess'] = true;
		return response()->json($data);
	}
}