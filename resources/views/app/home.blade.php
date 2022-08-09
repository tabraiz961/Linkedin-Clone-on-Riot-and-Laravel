@extends('layouts.app')
@php

$loggedUser = [];
if(Auth::check()){
	$loggedUser = App\Http\Controllers\UserController::setCuratedFields([Auth::user()])[0]->toArray();
	// echo('<pre>');print_r($loggedUser);echo('</pre>');die('call');
}	
@endphp
@section('content')
	<div id="home" class="grid-container">
		<div class="grid-x grid-margin-x">
			<div class="cell medium-4 large-3 hide-for-small-only" data-sticky-container>
				@include( 'app.inc.users.profile-card', [ 'user' => $loggedUser, 'sticky' => [ 'element' => 'timeline', 'top' => '3' ] ] )
			</div>
			<div class="cell small-12 medium-6 large-6" id="timeline">
				<post-form></post-form>
				<tag-infinite-scroller></tag-infinite-scroller>
			</div>

			<div class="cell small-12 medium-2 large-3" id="rightsidebar">
				<div class="cell">
					<h3>Connect People</h3>
					<tag-newprofiles></tag-newprofiles>
				</div>
				<div class="cell">
					<h3>Follow Institutions</h3>
		        	<tag-institutions></tag-institutions>
				</div>
			</div>
			{{-- <div class="cell " id="rightsidebar_institutions">
				
			</div> --}}
		</div>
	</div>
@endsection

@section('scripts')
	@parent
	@include( 'app.inc.tags' )

	<script>
		var post_visibility_list = [
			{icon:'fa fa-globe', 	visibletxt: '{{ trans("lang.post_visib_public") }}', 				value:'PUBLIC'},
			{icon:'fa fa-suitcase', visibletxt: '{{ trans("lang.post_visib_network") }}', 	value:'NETWORKMEMBERS'},
			{icon:'fa fa-users', 	visibletxt: '{{ trans("lang.post_visib_friend") }}', 	value:'FRIENDS'},
			{icon:'fa fa-user', 	visibletxt: '{{ trans("lang.post_visib_restricted") }}', value:'RESTRICTED'}
		];

		function onPostSubmit( form, tag ) {
			tag.disable();

			function onProgress( progressEvent ) {
				tag.setProgress( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
			}
			
			// for (var key of form.entries()) {
			// 	console.log(key[0] + ', ' + key[1]);
			// }
			// console.log(form);
			// return false;
			window.axios.post( '{{ url( "api/post" ) }}', form, { headers: { 'content-type': 'multipart/form-data' }, onUploadProgress: onProgress } )
				.then( function( response ) {
					tag.clear();
					tag.setProgress( 0 );
					tag.enable();
					window.infiniteScroller.reload();
				} ).catch( function( error ) {
				tag.enable();
				tag.setProgress( 0 );
				console.log( error );
			} );
		}
		var fileDragActive = false;
		document.querySelector('#timeline').addEventListener('dragenter', event => {
				event.preventDefault();
				// console.log('enter');
				this.fileDragActive = true;
		});	
		
		document.querySelector('#timeline').addEventListener('dragleave', event => {
				event.preventDefault();
				// console.log('leave');
				// fileDragActive = false;
		});	
		window.riot.mount( 'post-form', { 
			onSubmitted: onPostSubmit, 
			baseApiPath: '{{ url( 'api' ) }}',
			addsopts:{
				post_visibility: post_visibility_list,
				fileDragActive: fileDragActive
			}
		} );

		function getPostId( post ) {
			return post.post_id;
		}

		function loadPost( last_id, tag ) {
			window.axios.get( '{{ route( 'api.user.timeline' ) }}' + ( last_id !== null ? '/' + last_id : '' ) )
				.then( function( response ) {
					__post__loadAdditional( '{{ url( 'api' ) }}', response.data, tag.addItems, function( error ) {
						tag.error();
						console.log( error );
					} );
				}).catch( function( error ) {
					tag.error();
				console.log( error );
			} );
		}

		function getnewprofiles(last_id,tag) {
			console.log(tag);
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
		
		function getNewInstitutions(last_id,tag) {
			window.axios.get( '{{ route( 'api.networks.institutions' ) }}')
				.then( function( response ) {
					let _res = response.data;
					if(_res['success']){
						__post__loadAdditional( '{{ url( 'api' ) }}', _res.institutions, tag.addItems, function( error ) {
							tag.error();
							console.log( error );
						} );
					}
					
				}).catch( function( error ) {
					tag.error();
				console.log( error );
			} );
		}


		function setInfiniteScroller( tag ) {
			window.infiniteScroller = tag;
		}

		window.riot.mount( 'tag-newprofiles', {
			load: getnewprofiles,
			// component: 'post-renderer',
			scrollElement: document,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}',
				csrf_request: '{{ csrf_field() }}',
				// reloadProfile: getnewprofiles()
			},
			onMounted: setInfiniteScroller
			
		} );

		
		window.riot.mount( 'tag-institutions', {
			load: getNewInstitutions,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}',
				follow_ask: 'network/institutions/ask/'
			}
		} );
		window.riot.mount( 'tag-infinite-scroller', {
			load: loadPost,
			getItemId: getPostId,
			component: 'post-renderer',
			scrollElement: document,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}'
			},
			onMounted: setInfiniteScroller
		} );
	</script>
@endsection
