@extends('layouts.app')

@section('content')
	{{-- @if( env( 'ALGOLIA_APP_ID', '' ) === '' )
		<div class="callout alert">
			La recherche n'est pas configurée dans l'application.
		</div>
	@else --}}
		<div id="home" class="grid-container">
			<div class="grid-x grid-margin-x">
				<div class="cell medium-4 large-3 hide-for-small-only">
					@include( 'app.inc.users.profile-card', [ 'user' => Auth::user() ] )
					<div class="callout card search-companion">
						<div class="card-section">
							<ul class="accordion" data-accordion>
								<li class="accordion-item is-active" data-accordion-item>
									<a href="#" class="accordion-title">Names</a>
									<div class="accordion-content" data-tab-content>
										<div id="refinement-list-surname"></div>
									</div>
								</li>
								<li class="accordion-item" data-accordion-item>
									<a href="#" class="accordion-title">First names</a>
									<div class="accordion-content" data-tab-content>
										<div id="refinement-list-name"></div>
									</div>
								</li>
								<li class="accordion-item" data-accordion-item>
									<a href="#" class="accordion-title">Statuts</a>
									<div class="accordion-content" data-tab-content>
										<div id="refinement-list-title"></div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="cell small-12 medium-8 large-9">
					<search-timeline></search-timeline>
					
					<tag-infinite-scroller></tag-infinite-scroller>
				</div>
			</div>
		</div>
	{{-- @endif --}}
@endsection

@section('scripts')
	@parent
		@include('app.inc.search.tags')
		@include('app.inc.tags')

	<script>
		let searchKeyword = location.search.split('=')[1];
		if(searchKeyword == ''){
			searchKeyword = location.hash;
		}
		window.riot.mount('search-timeline',{
			searchUrlWidKeyword: "{{route('api.search.header')}}",
			toggle_institute_ask: "{{route('api.networks.institutions.toggle')}}",
			toggle_people_ask: "{{route('api.friend.toggle.request')}}",
			searchKeyword:  searchKeyword,
			saveSearch: true
		});
		
		function loadPost(last_id, tag, searchKeyword = null) {
			window.axios.get( '{{ route( 'api.user.timeline' ) }}' + ( last_id !== null ? '/' + last_id : '' ),{params: searchKeyword} )
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
		function getPostId( post ) {
			return post.post_id;
		}
		function setInfiniteScroller( tag ) {
			window.infiniteScroller = tag;
		}
		window.riot.mount( 'tag-infinite-scroller', {
			load: loadPost,
			getItemId: getPostId,
			component: 'post-renderer',
			scrollElement: document,
			addsopts: {
				basepath: '{{ url( '/' ) }}',
				baseapipath: '{{ url( 'api' ) }}',
				currentuserid: '{{ Auth::user()->user_id }}',
				searchType: true, 
				searchKeyword: searchKeyword
			},
			onMounted: setInfiniteScroller
		} );
	</script>
@endsection