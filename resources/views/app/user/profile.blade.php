
@php($title = $user['curated_name'])
@extends('layouts.app')

@section('content')

	<div class="grid-container">
		<div class="grid-x grid-padding-x grid-margin-y">
			<div class="large-8 medium-8 cell">
				<div class="callout profile-header-card no-border profile-page">
					@include( 'app.inc.users.myprofile-card', [ 'user' => $user, 'frame' => false ] )

					{{-- @guest 
						<div class="interact">
							<a href="{{ url('/') }}">Connect to interact</a>
						</div>
					@else --}}
						{{-- @if( Auth::user()->role == 'ADMIN' || Auth::user()->isSame( $user ) ) --}}
							{{-- {!! Form::open(['route' => [ 'user.delete', $user->username ], 'class' => 'remove_form']) !!}
								{{ method_field('DELETE') }}
								<button type="submit" class="remove button tiny alert"><i class="fas fa-times"></i></button>
							{!! Form::close() !!} --}}
							{{-- <button type="button" class="edit button tiny secondary" data-toggle="profileEditModal"><i class="fas fa-edit"></i></button> --}}
						{{-- @endif --}}
						{{-- @if( !Auth::user()->isSame( App\User::whereUserId($user['user_id'])->first() ))
							<div class="interact">
								@if( $user->isInNetwork( Auth::user() ) )
									@include( 'app.inc.buttons.remove.network', [ 'username' => $user->username ] )
								@else
									@include( 'app.inc.buttons.add.network', [ 'username' => $user->username ] )
								@endif

								@if( Auth::user()->isFriend( $user ) )
									@include( 'app.inc.buttons.remove.friend', [ 'username' => $user->username ] )

								@elseif (Auth::user()->askedFriend( $user ) )
									@include( 'app.inc.buttons.remove.request.remove', [ 'username' => $user->username ] )

								@elseif (Auth::user()->wasAskedFriend( $user ) )
									@include( 'app.inc.buttons.add.request', [ 'username' => $user->username ] )
									@include( 'app.inc.buttons.remove.request.refuse', [ 'username' => $user->username ] )

								@else
									@include( 'app.inc.buttons.add.friend', [ 'username' => $user->username ] )
								@endif
							</div>
						@endif --}}
					{{-- @endguest --}}
				</div>

				<profile-skills></profile-skills>
				@auth
					<experience-renderer></experience-renderer>
					<education-renderer></education-renderer>
					{{-- <skill-renderer></skill-renderer> --}}
				@endauth
			</div>

			{{-- <div class="large-4 medium-4 cell">
			</div> --}}
			{{-- @auth --}}
			<div class="large-4 medium-4 cell profile-network-card">
				<tag-profilepeople></tag-profilepeople>
			</div>
			{{-- @endauth --}}
		</div>
	</div>

	@auth
		@if( Auth::user()->hasFullEditRight() || $user['isSame']  )
			<div class="reveal" id="profileEditModal" data-reveal data-close-on-click="true" data-animate="spin-in spin-out" >
				@include( 'app.inc.forms.user-edit', [ 'user' => $user ])

				<button class="close-button" data-close aria-label="Close reveal" type="button">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
	@endauth
@endsection

@section('scripts')
	@parent
	@include('app.inc.tags')

		{{-- @if($errors->any())
			<script>
				$( '#profileEditModal' ).foundation( 'open' );
			</script> 
		@endif --}}
		<script>
			function getnewprofiles(last_id,tag) {
				window.axios.get( '{{ route( 'api.user.newprofiles' ) }}')
					.then( function( response ) {
						let _res = response.data;
						for (let index = 0; index < _res.length; index++) {
							const _infos = JSON.parse(_res[index].infos);
							_res[index].infos = _infos;
						}
						// addItems
						//console.log(//);
						__post__loadAdditional( '{{ url( 'api' ) }}', _res, tag.addItems, function( error ) {
							tag.error();
							console.log( error );
						} );
						
					}).catch( function( error ) {
						tag.error();
					console.log( error );
				} );
			}
			
			let canEdit = {{ Auth::user() &&  ( Auth::user()->user_id == $user['user_id'] ) ? 'true' : 'false' }};
			let authenticated = {{ Auth::check() ? 'true' : 'false' }};
			let canClick = !canEdit;
			let username = '{!! str_replace( "'", "\'", $user['username'] ) !!}';
			let infos = '{!! str_replace( "'", "\'", $user['infos'] ) !!}';
			let jobExperiences = JSON.parse('{!!  json_encode($user['job_experience'])  !!}');
			let educationExperiences = JSON.parse('{!!  json_encode($user['education_experience'])  !!}');
			let profileSkills = JSON.parse('{!!  json_encode($user['profile_skills'])  !!}');
			infos = ( infos.length > 0 ? JSON.parse( infos ) : {} );
			window.riot.mount( 'experience-renderer', { baseapipath: '{{ url( 'api' ) }}', basepath: '{{ url( '/' ) }}', initialitems: jobExperiences, username: username, canedit: canEdit } );
			window.riot.mount( 'education-renderer', { baseapipath: '{{ url( 'api' ) }}',  basepath: '{{ url( '/' ) }}', initialitems: educationExperiences, username: username, canedit: canEdit } );
			window.riot.mount( 'profile-skills', { 
				baseapipath: '{{ url( 'api' ) }}',  
				basepath: '{{ url( '/' ) }}', 
				skill_appreciate_link: '{{ route( 'user.profile.skill.appreciate',['username' =>  $user['username']] ) }}',  
				skill_profile_update_link: '{{ route( 'user.profile.skill.update',['username' =>  $user['username']] ) }}',  
				initialitems: profileSkills, 
				username: username, 
				canedit: canEdit,
				authenticated: authenticated, 
				canClick: canClick
			} );
			@auth
				window.riot.mount( 'tag-profilepeople', {
					load: getnewprofiles,
					// scrollElement: document,
					addsopts: {
						basepath: '{{ url( '/' ) }}',
						baseapipath: '{{ url( 'api' ) }}',
						currentuserid: '{{ Auth::user()->user_id }}',
						csrf_request: '{{ csrf_field() }}',
					},
					
				} );
			@endauth
		</script>
@endsection