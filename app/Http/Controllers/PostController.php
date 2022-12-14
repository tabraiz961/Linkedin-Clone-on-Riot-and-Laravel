<?php

namespace App\Http\Controllers;

use App\HashtagProfile;
use App\Http\Helpers\HelpersFunctions;
use App\Post;
use App\Reaction;
use App\Snowflake;
use App\User;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller {
	public function access( $post_id ) {
		return DB::table( 'post_visibilities' )
		->select( 'user_id' )
		->where( 'post_id', '=', $post_id )
		->get();
	}

	public function subposts( Request $request ) {
		$finalAssocs = [];
		$assocs = DB::table( 'sub_posts' )->whereIn( 'parent_post_id', $request->get( 'ids' ) )->orderBy( 'child_post_id', 'DESC' )->get();
		foreach( $assocs as $assoc ) {
			if( !array_key_exists( $assoc->parent_post_id, $finalAssocs ) ) {
				$finalAssocs[ $assoc->parent_post_id ] = [];
			}
			array_push( $finalAssocs[ $assoc->parent_post_id ], $assoc->child_post_id );
		}
		return response()->json( $finalAssocs );
	}

	public function show( $post_id ) {
		return view( 'app.post.show', [ 'post_id' => $post_id ] );
	}

	public function get( $post_id ) {
		$post = Post::findOrFail( $post_id );
		$post->subposts = Post::join( 'sub_posts', 'posts.post_id', '=', 'sub_posts.child_post_id' )
			->where( 'sub_posts.parent_post_id', '=', $post_id )->get();
		$post->reactions = Reaction::wherePostId( $post_id )->get();
		return response()->json( $post );
	}

	public function gets( Request $request ) {
		$posts = Post::whereIn( 'post_id', $request->input( 'ids' ) )->orderBy( 'post_id', 'DESC' )->get();
		return response()->json( $posts );
	}

	public function create( Request $request ) {
		$jsonVars = Post::jsonVars;
		$params = $request->all();
		if( !isset( $params[ 'post_id' ] ) ) {
			$params[ 'post_id' ] = Snowflake::create( with( new Post )->getTable() );
		}
		
		if($request->file( 'files' )){
			$path = Utils::store( Auth::user(), $request->file( 'files' ), 'images' );
			$params[ 'image_url' ] = \App\Utils::getFileUrl( $path );
		}

		$params[ 'author_id' ] = Auth::user()->user_id;

		if( !isset( $params[ 'type' ] ) ) {
			$params[ 'type' ] = 'POST';
		}

		foreach( $jsonVars as $jsonVar ) {
			if( isset( $params[ $jsonVar ] ) && is_array( $params[ $jsonVar ] ) ) {
				$params[ $jsonVar ] = json_encode( $params[ $jsonVar ]);
			}
		}
		// echo('<pre>');print_r($params);echo('</pre>');die('call');
		$validator = Validator::make( $params, Post::validation );
		
		if( $validator->fails() ) {
			return response()->json( [ 'errors' => $validator->errors() ], 420 );
		}

		foreach( $jsonVars as $jsonVar ) {
			if( isset( $params[ $jsonVar ] ) ) {
				$params[ $jsonVar ] = json_decode( $params[ $jsonVar ], true );
			}
		}

		$posts_ids = [];
		if( isset( $params[ 'photo_ids' ] ) ) {
			$posts_ids = array_merge( $params[ 'photo_ids' ], $posts_ids );
		}
		if( isset( $params[ 'video_ids' ] ) ) {
			$posts_ids = array_merge( $params[ 'video_ids' ], $posts_ids );
		}
		$post = Post::create( $params );
		$post->setSubposts( $posts_ids );
		if( isset( $params[ 'post_visibility_user_ids' ] ) && $params[ 'visibility' ] == 'RESTRICTED' ) {
			$post_visibility_user_ids = strpos($params[ 'post_visibility_user_ids' ], ',') ? explode(',',$params[ 'post_visibility_user_ids' ]) : [$params[ 'post_visibility_user_ids' ][0]]; 
			$post->setPostVisibility( $post_visibility_user_ids );
		}
		
		$tags = HelpersFunctions::getTagsArrayFromString($params['description']);
		foreach ($tags as  $value) {
			HashtagProfile::insert(['post_id'=> $post->post_id, 'hashtag' => $value]);
		}
		return response()->json( $post );
	}


	public function createImage( Request $request ) {
		return $this->createImageUser( $request, Auth::user()->username );
	}

	public function createImageUser( Request $request, $username ) {
		$user = User::whereUsername( $username )->firstOrFail();

		$params = $request->all();
		if( !isset( $params[ 'post_id' ] ) ) {
			$params[ 'post_id' ] = Snowflake::create( with( new Post )->getTable() );
		}

		$params[ 'author_id' ] = $user->user_id;
		$params[ 'type' ] = 'IMAGE';

		$validator = Validator::make( $params, Post::validation );

		if( !$validator->fails() ) {
			return response()->json( [ 'errors' => $validator->errors() ], 420 );
		}

		if( !$request->hasFile( 'image' ) ) {
			throw new \Exception( "Image not uploaded." );
		}
		$path = Utils::store( $user, $request->file( 'image' ), 'images' );
		$params[ 'image_url' ] = \App\Utils::getFileUrl( $path );

		$post = Post::create( $params );
		if( isset( $params[ 'post_visibility_user_ids' ] ) && $params[ 'visibility' ] == 'RESTRICTED' ) {
			$post->setPostVisibility( $params[ 'post_visibility_user_ids' ] );
		}
		return response()->json( $post );
	}

	public function createVideo( Request $request ) {
		return $this->createVideoUser( $request, Auth::user()->username );
	}

	public function createVideoUser( Request $request, $username ) {
		$user = User::whereUsername( $username )->firstOrFail();

		$params = $request->all();
		if( !isset( $params[ 'post_id' ] ) ) {
			$params[ 'post_id' ] = Snowflake::create( with( new Post )->getTable() );
		}

		$params[ 'author_id' ] = $user->user_id;
		$params[ 'type' ] = 'VIDEO';

		$validator = Validator::make( $params, Post::validation );

		if( !$validator->fails() ) {
			return response()->json( [ 'errors' => $validator->errors() ], 420 );
		}

		if( !$request->hasFile( 'video' ) ) {
			throw new \Exception( "Video not uploaded." );
		}
		$path = Utils::store( $user, $request->file( 'video' ), 'videos' );
		$params[ 'video_url' ] = \App\Utils::getFileUrl( $path );

		$post = Post::create( $params );
		if( isset( $params[ 'post_visibility_user_ids' ] ) && $params[ 'visibility' ] == 'RESTRICTED' ) {
			$post->setPostVisibility( $params[ 'post_visibility_user_ids' ] );
		}
		return response()->json( $post );
	}
// Request $request
	public static function trendingTags()
	{
		$hashtags = HashtagProfile::select(DB::raw('count(hashtag) as hashtag_count, hashtag'))
		->groupBy( 'hashtag')
		->orderBy('hashtag_count', 'desc')
		->limit(3)
		->get()
		->toArray();
		
		$data['data'] = $hashtags;
		$data['sucess'] = true;
		return response()->json($data);
	}
}