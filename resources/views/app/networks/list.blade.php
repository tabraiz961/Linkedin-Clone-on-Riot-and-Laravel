@php($title = "Votre réseau")
@extends('layouts.app')

@section('content')
	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<div class="cell medium-4 large-3 hide-for-small-only" data-sticky-container>
				@include( 'app.inc.users.profile-card', [ 'user' => $user, 'sticky' => [ 'element' => 'userList', 'top' => '4' ] ] )
			</div>
			<div class="cell small-12 medium-8 large-9" id="networkList">
				@if(count($inviters) > 0)
					<div class="callout card">
						<div class="card-divider">
							Invitations
						</div>
						<div class="card-section">
							@include( 'app.inc.users.list', [ 'users' => $inviters, 'buttons' => true ] )
						</div>
					</div>
				@endif
				<div class="callout card networkcard">
					<div class="card-divider back-0">
						<div class="space-between divided-headings-network">
							<div>{{trans("lang.network_people_may_know")}}</div>
							<div>{{trans("lang.network_viewall")}}</div>
						</div>
					</div>
					<div class="card-section know-people-container">
						<tag-people-may-know></tag-people-may-know>
						{{-- @include( 'app.inc.users.list', [ 'users' => $networkmembers, 'address' => $address ] ) --}}
					</div>
				</div>
				
				<div class="callout card networkcard">
					<div class="card-divider back-0">
						<div class="space-between divided-headings-network ">
							<div class="institutionName">{{trans("lang.network_people_may_know_institute")}} ... </div>
							<div>{{trans("lang.network_viewall")}}</div>
						</div>
					</div>
					<div class="card-section know-people-container">
						<know-from-experince-network></know-from-experince-network>
					</div>
				</div>

				<div class="callout card networkcard">
					<div class="card-divider back-0">
						<div class="space-between divided-headings-network ">
							<div >{{trans("lang.network_recommend_institute")}}</div>
							<div>{{trans("lang.network_viewall")}}</div>
						</div>
					</div>
					<div class="card-section know-people-container">
						<know-from-institutions></know-from-institutions>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection


@section('scripts')
	@parent
	
	<script data-src="{{ asset( 'tags/tag-people-may-know.tag' ) }}" type="riot/tag"></script>
	<script data-src="{{ asset( 'tags/know-from-experince-network.tag' ) }}" type="riot/tag"></script>
	<script data-src="{{ asset( 'tags/know-from-institutions.tag' ) }}" type="riot/tag"></script>

	<script>
		var asset_loc = "<?php echo(asset('images'). ''); ?>";
		function getPeopleYouMayKnow(tag) {
			window.axios.get( '{{ route( 'api.user.network.list' ) }}')
				.then( function( response ) {
					tag.addItems(response);
					// __post__loadAdditional( opts.addsopts.baseapipath, response, tag.addItems, function( _res ) {
					// 		console.log(_res);
					// 	}, function( error ) {
					// 		console.log(error);
					// 	});
					
				}).catch( function( error ) {
					// tag.error();
				console.log( error );
			} );
		}
		function getPeopleFromExperience(tag) {
			window.axios.get( '{{ route( 'api.network.users.experience' ) }}')
				.then( function( response ) {
					if(response.data.success && response.data.data){
						$('.institutionName').html('People You may know from <a>'+response.data.data.institution.institution_name+'</a>');
						tag.addItems(response.data.data.users);
					}
				}).catch( function( error ) {
					// tag.error();
					console.log( error );
				} );
		}
		function getInstitutions(tag) {
			window.axios.get( '{{ route( 'api.networks.institutions' ) }}')
				.then( function( response ) {
					console.log(response);
					if(response.data.success && response.data){
						tag.addItems(response.data.institutions);
					}
				}).catch( function( error ) {
					// tag.error();
					console.log( error );
				} );
		}
		// function asdasd(last_id, tag){
		// 	console.log(tag);
		// }
		window.riot.mount( 'tag-people-may-know', {
			load: getPeopleYouMayKnow,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}'
			}
		} );

		window.riot.mount( 'know-from-experince-network', {
			load: getPeopleFromExperience,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}'
			}
		} );

		window.riot.mount( 'know-from-institutions', {
			load: getInstitutions,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}'
			}
		} );
	</script>
@endsection
