<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\FriendRequestReceived;
use App\Notifications\FriendRequestAccepted;
use App\Post;
use App\User as AppUser;
use Laravel\Scout\Searchable;

/**
 * App\User
 *
 * @property int $user_id
 * @property string $name
 * @property string $surname
 * @property string $username
 * @property string $email
 * @property string|null $password
 * @property string $role
 * @property string $birth_date
 * @property int $address_id
 * @property string|null $title
 * @property string|null $cv_url
 * @property int|null $photo_id
 * @property int|null $cover_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthDate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCoverId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCvUrl( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhotoId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSurname( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTitle( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername( $value )
 * @mixin \Eloquent
 */
class User extends Authenticatable {
	use Notifiable;

	protected $table = 'users';
	public $timestamps = true;

	protected $primaryKey = 'user_id';
	public $incrementing = true;

	// const CREATED_AT = 'users.created_at';
	// const UPDATED_AT = 'users.updated_at';

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
		'photo_path',
		'cover_path',
		'infos',
		'remember_token'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be validated and their respective format
	 */
	const validation = [
		'username' => 'required|string|max:255',
		'email' => 'required|string|email|max:255|unique:users',
		'name' => 'required|string|max:255',
		'surname' => 'required|string|max:255',
		'password' => 'required|string|min:6|confirmed',
		'birth_date' => 'required|date',
		'title' => 'string',
		'infos' => 'json|nullable',
		'cv' => 'mimes:pdf|nullable',
		// 'photo_path' => 'string',
		// 'cover_path' => 'string',
		// 'g-recaptcha-response' => 'required|captcha'
	];

	/**
	 * The attributes that should be validated and their respective format on update
	 */
	const validation_update = [
		'username' => 'string|max:255',
		'email' => 'string|email|max:255|unique:users',
		'name' => 'string|max:255',
		'surname' => 'string|max:255',
		'birth_date' => 'date',
		'title' => 'string|nullable',
		'cv' => 'mimes:doc,pdf,docx,jpeg,bmp,png|nullable',
		// 'photo_path' => 'string',
		// 'cover_path' => 'string',
		'infos' => 'json|nullable',
		'password' => 'string|nullable|confirmed'
	];

	const select = [
		'name',
		'surname',
		'username',
		'email',
		'role',
		'birth_date',
		'title',
		'cv_url'
	];

	const select_more = [
		'created_at',
		'updated_at'
	];

	const searchable_fields = [ 'username', 'cv_url', 'photo_path', 'name', 'surname', 'title', 'infos', 'address_id' ];

	const default_photo_url = '/images/avatar.png';

	// Max usage in MB
	const quota_usage_max = 200;

	// public function newEloquentBuilder( $query ) {
	// 	return parent::newEloquentBuilder( $query )
	// 		->leftJoin( 'posts AS photo', 'users.photo_id', '=', 'photo.post_id' )
	// 		->leftJoin( 'posts AS cover', 'users.cover_id', '=', 'cover.post_id' )
	// 		->addSelect( '*' )
	// 		->addSelect( 'photo.image_url AS photo_url' )
	// 		->addSelect( 'cover.image_url AS cover_url' );
	// }

	// public function getPhoto() {
	// 	return $this->hasOne( 'Post', 'photo_id' );
	// }
	public function profile()
    {
        return $this->hasOne('App\ProfileDetails', 'user_id','user_id');
    }
	public function getInstitution()
    {
        return $this->hasOne('App\Institutions','owner_id', 'user_id');
    }
	public function getAddress() {
		return $this->hasOne( 'App\Addresses', 'id', 'address_id' );
	}


	public function getConversations() {
		return $this->belongsToMany( 'Conversation', 'conversation_member', 'user_id', 'conversation_id' );
	}

	public function getFriendRequests() {
		return $this->hasMany( 'FriendRequest', 'invited_id' );
	}

	public function getNotifications() {
		return $this->hasMany( 'Notification' );
	}

	public function getName() {
		return $this->name . " " . $this->surname;
	}

	public function appliedJobs() {
		return $this->hasMany(JobApplied::class, 'user_id', 'user_id');
	}

	public function courses()
	{
		return $this->hasOne('App\CoursesUsers', 'user_id','user_id');
	}
	public function applyToJob($jobId, $user = null)
	{
		if(!$user){
			$user = Auth::user();
		}
		return JobApplied::insertGetId(['job_id' => $jobId, 'user_id' => $user->user_id]);
	}
	public function selectorNetworkMembers() {
		return Networks::where('user1_id', '=', Auth::user()->user_id);
		// return User::join( 'networks', 'users.user_id', '=', 'networks.user1_id' )
		// 	->join( 'users AS user2', 'user2.user_id', '=', 'networks.user2_id' )
		// 	->where( 'users.user_id', '=', $this->user_id )
		// 	->leftJoin( 'friendships', function( $join ) {
		// 		$join->on( 'friend2_id', '=', 'user2.user_id' )
		// 			->on( 'friend1_id', '=', 'users.user_id' );
		// 	} )
		// 	->where( 'user2.user_id', '!=', $this->user_id )
		// 	->addSelect( 'friend2_id AS isFriendOf' );
	}

	public function selectorFriends() {
		return User::join( 'friendships', 'users.user_id', '=', 'friendships.friend1_id' )
			->join( 'users AS user2', 'user2.user_id', '=', 'friendships.friend2_id' )
			->where( 'users.user_id', '=', $this->user_id );
	}

	public function selectorIsNetworkMember( User $user ) {
		return DB::table( 'networks' )
			->where( function( $query ) use ( $user ) {
				$query->where( 'user1_id', '=', $this->user_id )->where( 'user2_id', '=', $user->user_id );
			} );
	}

	public function selectorFriendship( User $user ) {
		return DB::table( 'friendships' )
			->where( function( $query ) use ( $user ) {
				$query->where( 'friend1_id', '=', $this->user_id )->where( 'friend2_id', '=', $user->user_id );
			} );
	}

	public function selectorTimeline($post_id, $searchKeyword = '') {

		// Get Networks followers 
		$temp = Networks::where('user1_id', Auth::user()->user_id)->get()->toArray();
		$followers = array_column($temp,'user2_id');
		// and me
		array_push($followers, Auth::user()->user_id);
		$afterQuery = '';
		if($post_id){
			$afterQuery = ' and posts.post_id < '. $post_id;
		}
		if($searchKeyword != ''){
			if($post_id){
				// $afterQuery .= ' and ';
			}
			$afterQuery .= ' and posts.description like "%'. $searchKeyword . '%" ';
		}
		$selectColumns = '';
		// foreach( Networks::select as $select ) {
		// 	$selectColumns .=  ' networks.' . $select . ' as '. $select . ',' ;
		// }
		foreach( Post::select as $select ) {
			$selectColumns .=  'posts.' . $select .',';
		}
		foreach( Post::select_more as $select ) {
			$selectColumns .=  'posts.' . $select .',';
		}
		foreach( User::select as $select ) {
			$selectColumns .= 'authors.' . $select .',';
		}
		// foreach( ['user_id', 'post_id'] as $select ) {
		// 	$selectColumns .= 'post_visibilities.' . $select .' as vis_'.$select .',';
		// }
		$selectColumns = rtrim($selectColumns, ", ");
		// echo('<pre>');print_r($selectColumns);echo('</pre>');die('call');
		
		// get network user on Not restricted posts 
		// or 
		// Get only my posts of last 3 hours for editing and updating purpose
		// or
		// Get restricted posts shown only to me 

		// Type POST
		// Limit 20 

		// join posts as posts2 on posts.post_id = posts2.post_id 
		return DB::select('select distinct '.$selectColumns.' from posts 
			left join networks on networks.user2_id = posts.author_id 
			join users as authors on authors.user_id = posts.author_id 
		
			left join post_visibilities   on posts.post_id = post_visibilities.post_id 
			where (
				(
					posts.visibility != "RESTRICTED" 
						and 
					posts.author_id != '. Auth::user()->user_id.' 
						and 
					networks.user1_id = '. Auth::user()->user_id.'
				) 
				or  
				( 
					posts.author_id = '. Auth::user()->user_id.' 
						and 
					posts.created_at > ( DATE_ADD(NOW(), INTERVAL 5 HOUR) - INTERVAL 4 HOUR )
				)
				or
				(
					posts.visibility  = 	"RESTRICTED" 
						and
					post_visibilities.user_id = '. Auth::user()->user_id .'
				)
			) '. 
			$afterQuery . 
			' and posts.type = "POST" '.
			' order by posts.post_id desc
			limit 20');
		// echo('<pre>');print_r($sel);echo('</pre>');die('call');
		// $sel =AppUser::whereIn('user_id', $followers)
		// 	->join('posts', 'posts.author_id', '=', 'users.user_id')
		// 	->join('posts AS asdasd ','posts.post_id', '=', 'asdasd.post_id')
		// 	->where('asdasd.author_id', '=', Auth::user()->user_id)
		// 	->orWhere('posts.visibility', '!=', 'RESTRICTED')->get()->toArray();
		
		// $sel =Post::whereIn('author_id', $followers)
			// ->join('users as users2', 'users2.user_id', '=', 'posts.author_id')
			// ->where('users2.user_id', '==',  Auth::user()->user_id);

			// $mylastDayPosts = Post::where('author_id','=',Auth::user()->user_id)
			// ->where('created_at')
			// orWhere('users', 'users.user_id', '=', Auth::user()->user_id);


			// $sel = Post::join( 'networks', 'networks.user1_id', '=', 'posts.author_id' )
			// ->leftJoin( 'friendships', function( $join ) {
			// 	$join->on( 'friend1_id', '=', 'posts.author_id' )
			// 		->on( 'friend2_id', '=', 'networks.user2_id' );
			// })->leftJoin( 'posts AS authorsImage', 'authorsImage.post_id', '=', 'authors.photo_path' )
			// ->where( 'networks.user2_id', '=', $this->user_id )
			// ->where( function( $query ) {
			// 	$query->where( 'posts.author_id', '=', $this->user_id )
			// 		->orWhere( 'posts.visibility', '=', 'PUBLIC' )
			// 		->orWhere( 'posts.visibility', '=', 'NETWORKMEMBERS' )
			// 		->orWhere( function( $query ) {
			// 			$query->where( 'posts.visibility', '=', 'FRIENDS' )
			// 				->whereNotNull( 'friend2_id' );
			// 		} )->orWhere( function( $query ) {
			// 			$query->where( 'posts.visibility', '=', 'RESTRICTED' )
			// 				->whereExists( function( $query ) {
			// 					$query->select( DB::raw( '*' ) )
			// 						->from( 'post_visibilities' )
			// 						->where( 'post_visibilities.post_id', '=', DB::raw( 'posts.post_id' ) )
			// 						->where( 'post_visibilities.user_id', '=', $this->user_id );
			// 				} );
			// 		} );
			// })->where( function( $query ) {
			// 	$query->where( 'posts.type', '=', 'POST' )
			// 		->orWhere( 'posts.type', '=', 'EVENT' )
			// 		->orWhere( 'posts.type', '=', 'SHARE' );
			// });
		// foreach( Post::select as $select ) {
		// 	$sel->addSelect( 'posts.' . $select . ' AS ' . $select );
		// }
		// foreach( Post::select_more as $select ) {
		// 	$sel->addSelect( 'posts.' . $select . ' AS ' . $select );
		// }
		// foreach( User::select as $select ) {
		// 	$sel->addSelect( 'authors.' . $select . ' AS ' . $select );
		// }
		// return $sel->addSelect( 'authorsImage.image_url AS photo_url' )
		// 	->orderBy( 'posts.post_id', 'DESC' );
	}

	public function getNetworkMembers() {
		return $this->selectorNetworkMembers()->get();
		//return DB::select('SELECT * FROM users AS user1 JOIN networks ON user1.user_id = networks.user1_id JOIN users AS user2 ON user2.user_id = networks.user2_id WHERE user1.user_id = :id OR user2.user_id = :id;', ['id' => $this->user_id]);
	}

	public function getFriends() {
		return $this->selectorFriends()->get();
	}

	public function getTimeline() {
		return $this->selectorTimeline()->get();
	}

	public function isSame( User $user ) {
		if(is_object($user)){
			return $this->user_id === $user->user_id;
		}
		return $this->user_id === $user['user_id'];
	}

	public function isInNetwork( User $user ) {
		return $this->selectorIsNetworkMember( $user )->first() !== null;
	}

	public function isFriend( User $user ) {
		return $this->selectorFriendship( $user )->first() !== null;
	}

	public function hasFriendOf() {
		return array_key_exists( 'isFriendOf', $this->toArray() );
	}

	public function askedFriend( User $user ) {
		$status = DB::table( 'friend_requests' )
			->where( 'invited_id', '=', $user->user_id )
			->where( 'requester_id', '=', $this->user_id )
			->first();
		return $status !== null;
	}

	public function wasAskedFriend( User $user ) {
		$status = DB::table( 'friend_requests' )
			->where( 'invited_id', '=', $this->user_id )
			->where( 'requester_id', '=', $user->user_id )
			->first();
		return $status !== null;
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function addToNetwork( User $user ) {
		if( $this->isSame( $user ) ) {
			throw new \Exception( "Cannot add yourself to your network." );
		}
		$status = DB::table( 'networks' )->insert( [
			'user1_id' => $this->user_id,
			'user2_id' => $user->user_id
		] );
		// $status = DB::table( 'networks' )->insert( [
		// 	'user1_id' => $user->user_id,
		// 	'user2_id' => $this->user_id
		// ]);
		return $status;
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function addFriend( User $user ) {
		if( $this->isSame( $user ) ) {
			throw new \Exception( "Cannot add yourself as friend." );
		}
		
		// If not in Network then add to network
		if( $this->selectorIsNetworkMember( $user )->first() == null) {
			$this->addToNetwork( $user );
		}

		$this->delReceivedFriendRequest( $user );

		$status = DB::table( 'friendships' )->insert( [
			'friend1_id' => $this->user_id,
			'friend2_id' => $user->user_id
		] );
		$status &= DB::table( 'friendships' )->insert( [
			'friend2_id' => $this->user_id,
			'friend1_id' => $user->user_id
		] );

		$user->notify( new FriendRequestAccepted( $this ) );

		return $status;
	}

	public function sendFriendRequest( User $user ) {
		$status = true;
		//send friend request
		//check if selected user is not the same user
		if( $this->isSame( $user ) ) {
			throw new \Exception( "Cannot add yourself as friend." );
		}
		//create request
		$status = DB::table( 'friend_requests' )->insert( [
			'requester_id' => $this->user_id,
			'invited_id' => $user->user_id
		] );
		$count = DB::table('networks')
			->where('user1_id', $this->user_id)
			->where('user2_id', $user->user_id)
			->first();


		if(!$count){
			$status = DB::table( 'networks' )->insert( [
				'user1_id' => $this->user_id,
				'user2_id' => $user->user_id
			] );
		}
		$user->notify( new FriendRequestReceived( Auth::user() ) );

		return $status;
	}

	public function delReceivedFriendRequest( User $user ) {
		//delete friend request

		//create request
		$status = DB::table( 'friend_requests' )
			->where( 'invited_id', '=', $this->user_id )->where( 'requester_id', '=', $user->user_id )
			->delete();
		return $status;
	}

	public function delSentFriendRequest( User $user ) {
		//delete friend request

		//create request
		$status = DB::table( 'friend_requests' )
			->where( 'requester_id', '=', $this->user_id )->where( 'invited_id', '=', $user->user_id )
			->delete();
		return $status;
	}

	/**
	 * @param User $user
	 *
	 * @return int
	 * @throws \Exception
	 */
	public function removeFromNetwork( User $user ) {
		if( $this->isSame( $user ) ) {
			throw new \Exception( "Illegal removing oneself from his network." );
		}
		if( $this->isFriend( $user ) ) {
			$this->removeFriend( $user, true );
		}

		/*return DB::table( 'networks' )
			->where( function( $query ) use ( $user ) {
				$query->where( 'user1_id', '=', $this->user_id )->where( 'user2_id', '=', $user->user_id );
			} )->orWhere( function( $query ) use ( $user ) {
				$query->where( 'user2_id', '=', $this->user_id )->where( 'user1_id', '=', $user->user_id );
			} )->delete();*/
		DB::table( 'networks' )
		->where( 'user2_id', '=', $this->user_id )->where( 'user1_id', '=', $user->user_id )
		->delete();
	}

	/**
	 * @param User $user
	 * @param bool $skipNetwork
	 *
	 * @return int
	 * @throws \Exception
	 */
	public function removeFriend( User $user, $skipNetwork = true ) {
		if( $this->isSame( $user ) ) {
			throw new \Exception( "Illegal removing oneself as friend." );
		}
		if( !$skipNetwork && !$this->isInNetwork( $user ) ) {
			$this->removeFromNetwork( $user );
		}

		return DB::table( 'friendships' )
			->where( function( $query ) use ( $user ) {
				$query->where( 'friend1_id', '=', $this->user_id )->where( 'friend2_id', '=', $user->user_id );
			} )->orWhere( function( $query ) use ( $user ) {
				$query->where( 'friend2_id', '=', $this->user_id )->where( 'friend1_id', '=', $user->user_id );
			} )->delete();
	}

	public function selectorImages() {
		return Post::where( 'author_id', '=', $this->user_id )
			->where( 'type', '=', 'IMAGE' )
			->orderBy( 'post_id', 'DESC' );
	}

	public function selectorVideos() {
		return Post::where( 'author_id', '=', $this->user_id )
			->where( 'type', '=', 'VIDEO' )
			->orderBy( 'post_id', 'DESC' );
	}

	public function selectorEvents() {
		return Event::where( 'author_id', '=', $this->user_id )
			->orderBy( 'event_id', 'DESC' );
	}

	public function setCV( $file ) {
		if( $this->cv_url != null ) {
			Storage::delete( $this->cv_url );
		}
		$this->cv_url = Utils::store( $this, $file, 'cvs' );
	}

	public function getBirthDateAttribute( $value ) {
		return \Carbon\Carbon::parse( $value )->format( 'Y-m-d' );
	}

	/**
	 * Get the user's date of birth for forms.
	 *
	 * @param  string $value
	 *
	 * @return string
	 */
	public function formBirthDateAttribute( $value ) {
		return \Carbon\Carbon::parse( $value )->format( 'Y-m-d' );
	}

	public function setPassword( $password ) {
		$this->password = Hash::make( $password );
	}

	public static function boot() {
		parent::boot();

		// self::created(function($model){
		// 	DB::table( 'networks' )->insert([
		// 		'user1_id' => $model->user_id,
		// 		'user2_id' => $model->user_id
		// 	]);
		// });
	}

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
		$origArray = $this->toArray();
		$array = [];

		foreach( User::searchable_fields as $searchableField ) {
			if( isset( $origArray[ $searchableField ] ) ) {
				$array[ $searchableField ] = $origArray[ $searchableField ];
			} else {
				$array[ $searchableField ] = null;
			}
		}

		return $array;
	}

	/**
	 * Send the password reset notification.
	 *
	 * @param  string $token
	 * @return void
	 */
	public function sendPasswordResetNotification( $token ) {
		$this->notify( new ResetPassword( $token ) );
	}

	public function hasFullEditRight() {
		return $this->role == 'ADMIN';
	}

	public function hasInstitution()
	{
		return Auth::user()->getInstitution->toArray() != [];
	}
	
}