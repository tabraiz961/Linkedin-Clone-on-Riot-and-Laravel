@php
	if(!isset($is_index)) {
		$is_index = false;
	}
	if(!isset($same_background)) {
		$same_background = false;
	}
	if(!isset($vertically_centered)) {
		$vertically_centered = false;
	}
	$user = null;
	use App\Http\Controllers\UserController as UserControllerTemp;

	
	
	if(Auth::check()){
		$user = UserControllerTemp::setCuratedFields([Auth::user()])[0];
		if($user->getInstitution){
			$user->getInstitution = \App\Http\Controllers\InstitutionController::getCuratedInstitution($user->getInstitution->toArray());
		}
		$user->courses;
	}
	// echo('<pre>');print_r($user);echo('</pre>');die('call');
@endphp
@section( 'scripts' )
	<script src="{{ asset('js/app.js') }}"></script>
	
	<script src="{{ asset('js/core/riot+compiler.min.js') }}"></script>
	<script src="{{ asset('js/core/select2.min.js') }}"></script>
	<script src="{{ asset('js/core/video.min.js') }}"></script>
	<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/libraries/notify.min.js') }}"></script>
	{{-- <script src="{{ asset('js/custom.js') }}?t={{time()}}"></script> --}}
	<script> 
		$(document).foundation();
	</script>
	
	<script data-src="{{ asset( 'tags/home/header-search.tag' ) }}" type="riot/tag"></script>

@endsection

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	{{-- <link rel="manifest" href="{{ asset('manifest.json') }}"> --}}

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ isset( $title ) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}?t={{time()}}" rel="stylesheet">
	@yield( 'styles' )

	<!-- Icons & Colors -->
	<meta name="theme-color" content="#23a3ba">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="icon" sizes="64x64" href="{{ asset('images/icon-fav.png') }}">
	<link rel="icon" sizes="310x310" href="{{ asset('images/icon-pin.png') }}">
	<link rel="apple-touch-icon" href="{{ asset('images/icon-pin.png') }}">
	<meta name="msapplication-square310x310logo" content="{{ asset('images/icon-pin.png') }}">

	{{-- Select2  JS Also Included --}}
	<link href="{{ asset('css/core/select2.min.css') }}" rel="stylesheet" />

	{{-- Jquery  UI Also Included --}}
	<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/core/video-js.css') }}" rel="stylesheet" />

</head>
<body class="@if($same_background) same-background @endif @if($vertically_centered) vertically-centered @endif">
	<div id="overlay_search_container" ></div>
	<div id="app">
		<div data-sticky-container class="grid-container fluid" ref="parent_nav_container">
			<nav id="menubar" class="p-0 top-bar">
				<div class="grid-container menubar-container large-grid-frame">
					<div class="top-bar-left">
						<ul class="menu">
							@auth
								<li class="hide-for-small-only">
									<a class="p-0" href="{{route( 'index' )}}">
										<img class="header-default-logo" src="{{asset('images/logo-menu-full.png')}}" alt="Logo">
									</a>
								</li>
								<li class="hide-for-small-only"> 
									<header-search></header-search>
								</li>
								<li class="show-for-small-only">
									<a href="{{ route( 'search.user' ) }}"><i class="fas fa-search"></i></a>
								</li>
							@else
								<li class="header-default-logo-notLogged">
									<img src="{{asset('images/logo-menu-full.png')}}" alt="Logo">
								</li>
							@endauth
						</ul>
					</div>

					@if(!$is_index)
						<div class="top-bar-right" id="header_right_bar_actions">
							<ul class="dropdown menu header_right_bar_actions_dropdown"  data-dropdown-menu data-disable-hover="true" data-click-open="true" ref="notifi_drop">
								@auth 
									{{-- @if( Auth::user()->hasFullEditRight() ) --}}
										<li class="hide-for-small-only"><a href="{{ route( 'index' ) }}" title="Users">Home</a></li>
										{{-- <li class="hide-for-small-only"><a  title="Offres d'emploi">Classes</a></li>
										<li class="hide-for-small-only"><a href="{{ route( 'user.list' ) }}" title="Users">Parents</a></li> --}}
										@if (Session::has('UserType')== HelpersFunctions::LOGGEDUSER)	
											<li class="hide-for-small-only"><a href="{{ route( 'user.network.list' ) }}" title="Mon réseau">Find Connections</a></li>
											<li class="hide-for-small-only"><a href="{{ route( 'courses.all.public') }}" title="Courses">Courses</a></li>
										@endif
										<li class="hide-for-small-only"><a href="{{ route( 'job.list' ) }}" title="Offres d'emploi">Jobs</a></li>
										{{-- <li class="hide-for-small-only"><a href="{{ route( 'entity.list' ) }}" title="Companies/Schools"><i class="fas fa-envelope-square"></i></a></li> --}}
									{{-- @else
										<li class="hide-for-small-only"><a href="{{ route( 'entity.list.own' ) }}" title="My companies/schools"><i class="fas fa-building"></i></a></li>
									@endif --}}
									<!-- <li><a href="{{ route( 'job.list' ) }}" title="Offres d'emploi"><i class="fas fa-suitcase"></i></a></li> -->
									<!-- <li><a href="{{ route( 'user.network.list' ) }}" title="Mon réseau"><i class="fas fa-users"></i></a></li> -->
									<li>
										<a href="#">
											<i class="fas fa-bell"></i><span class="badge notification-badge">{{count(auth()->user()->unreadNotifications->sortByDesc('created_at'))}}</span>
										</a>
										<ul id="notificationPanel" class="menu vertical popover-panel border-rad-10">
											@foreach($data['latest_notifications'] as $notification)
												@php
													$filename = preg_split( "/\\\\/", $notification['type'] );
													$filename = strtolower( $filename[ count( $filename ) - 1 ] );
												@endphp
													@include( 'app.inc.notifications.' . $filename , [ 'notification' => $notification ])
											@endforeach
										</ul>
									</li> 

									<li class="header-profile-toggler">
										<a class="header-profile-anchor" href="{{ route( 'user.profile', [ 'username' => Auth::user()->username ] ) }}">
											<img src="{{$user->photo_id}}" class="user" alt="">
										</a>
										<ul id="header_profile_drop"  data-alignment="right" class="menu vertical popover-panel">
											<li>
												<a  href="{{ route( 'user.profile', [ 'username' => Auth::user()->username ] ) }}" class="flex-container-forced">
													<div class="header-drop-dp">
														<img src="{{$user->photo_id}}" class="user" alt="">
													</div>
													<div class="header-drop-details flex-container flex-dir-column ml-10">
														<div class="font18">
															<b>{{ $user->name.' '.$user->surname }}</b>
														</div>
														<div class="mt-5 font14">{{ $user->title }}</div>
													</div>
												</a>
											</li>

											<li>
												<a>
													<div class="text-center">
														Settings
													</div>
												</a>
											</li>
											@if (!Auth::user()->getInstitution)	
												<li>
													<a data-open="create-page-modal-toggle">
														<div class="text-center">Create a Page</div>
													</a>
												</li>
											@else
												@php
													$institution = Auth::user()->getInstitution;
												@endphp
												<li id="profile-header-institution">
													<a href="{{route('institutions.load', $institution['slug'])}}" class="flex-container-forced">
														<div>
															<img src="{{ $institution['institution_photo'] }}" alt="" class="institution">
														</div>
														<div class=" ml-10" style="color: #353535;">{{ $institution['institution_name'] }}</div>
													</a>
												</li>
												<li  class="text-center">
													<a href="{{route('job.list', $institution['slug'])}}">
														<div class="text-center">
															My Jobs
														</div>
													</a>
												</li>
												
												<li  class="text-center">
													<a href="{{route('job.applications.page')}}">
														<div class="text-center">
															Job Applications 
														</div>
													</a>
												</li> 
												@if (isset($user->courses) && count($user->courses->toArray()))
													<li  class="text-center">
														<a href="{{route('courses.mycourse.list')}}">
															<div class="text-center">
																My Courses
															</div>
														</a>
													</li>
												@endif
											@endif

											@if( Auth::user()->hasFullEditRight() )
												<li class="show-for-small-only"><a href="{{ route( 'user.list' ) }}" title="Users">Users</a></li>
												<li class="show-for-small-only"><a href="{{ route( 'entity.list' ) }}" title="Companies/Schools">Companies/Schools</a></li>
											@else
												<li class="show-for-small-only"><a href="{{ route( 'entity.list.own' ) }}" title="My companies/schools">My companies/schools</a></li>
											@endif
											<li>
												<a class="text-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
													Logout
												</a>

												<form id="logout-form" action="{{ route('logout') }}" method="POST"
													style="display: none;">
													{{ csrf_field() }}
												</form>
											</li>
										</ul>
									</li>
								@else
									<li class="hide-for-small-only"><a href="{{ url( '/' ) }}">Login</a></li>
									<li class="hide-for-small-only"><a href="{{ url( '/#register' ) }}">Inscription</a></li>
									<li class="show-for-small-only"><a href="{{ url( '/' ) }}"><i class="fas fa-sign-in-alt"></i></a></li>
								@endauth
							</ul>
						</div>
					@endif
				</div>
			</nav>
		</div>

		@if( isset( $profile_sidebar ) && $profile_sidebar )
			<div id="home" class="grid-container">
				<div class="grid-x">
					<div class="cell medium-4 large-3 hide-for-small-only" data-sticky-container>
						@php
						if( isset( $profile_sticky ) && !$profile_sticky ) {
							$carddata = [ 'user' => $user ];
						} else {
							$carddata = [ 'user' => $user, 'sticky' => [ 'element' => 'userList', 'top' => '4' ] ];
						}
						@endphp
						@include( 'app.inc.users.profile-card', $carddata )
						@yield('sidebar')
					</div>
					<div class="cell small-12 medium-8 large-9" id="userList">
						@yield('content')
					</div>
				</div>
			</div>
		@else
			@yield('content')
		@endif
	</div>


	<div class="footer">
		<div class="copyright">
			
		</div>

		<div class="nav">
			<ul class="mainnav"><li><a href="#">Issac Issah Armstrong </a></li><li><a href="#">User Agreement</a></li><li><a href="#">Privacy Policy</a></li><li><a href="#">Community Guidelines</a></li>
				<li><a href="#">Cookie Policy</a></li></ul>
			
		</div>
		

	</div>

	<div class="border-rad-15 flex-container flex-dir-column reveal tiny" id="create-page-modal-toggle" aria-labelledby="exampleModalHeader11" data-reveal style="align-items: center;">
		<p id="exampleModalHeader11">Name of Institution</p>
		<input placeholder="Some Important University" class="uni_name"><button id="create_uni_btn" onclick="createUni()" class="button">Submit</button>
		<button class="close-button" data-close="" aria-label="Close Accessible Modal" type="button">
		<span aria-hidden="true">×</span>
		</button>
	</div>



	{{-- <div class="border-rad-15 flex-container flex-dir-column reveal tiny" id="create-page-modal-toggle" aria-labelledby="exampleModalHeader11" data-reveal="" role="dialog" aria-hidden="false" data-yeti-box="create-page-modal-toggle" data-resize="create-page-modal-toggle" data-he="o9cn31-he" tabindex="-1" style="align-items: center;">
		<p id="exampleModalHeader11">Name of Institution</p>
		<input placeholder="Some Important University" class="uni_name" onclick=""><button id="create_uni_btn" onclick="createUni(this)" class="button">Submit</button>
		<button class="close-button" data-close="" aria-label="Close Accessible Modal" type="button">
		<span aria-hidden="true">×</span>
		</button>
	</div> --}}

	<!-- Scripts -->
	@yield( 'scripts' )
	
	
	@include( 'app.inc.alerts.alerts' )
	<script>

		$( '#notificationPanel li .read-action' ).click( function() {
			let notificationLine = $( this ).parent().parent();
			let notification_id = notificationLine.attr( 'data-notification-id' );
			window.axios.post( '{{ url('api/notification') }}/' + notification_id + '/read' ).then( function( response ) {
				notificationLine.remove();
				console.log( response );
			} ).catch( function( error ) {
				notificationLine.remove();
				console.log( error );
			} );
		} );
		function accept(user, e) {
			if(user){
				window.axios.put( '{{ url('friend') }}/' + user,{'noti_id': e.closest('li').getAttribute('data-notification-id') } ).then( function( response ) {
					console.log(response);
					if(response['success']){
						e.closest('li').querySelector('.noti_det_child a').innerHTML = "<b>"+ response['friend']['curated_name'] +"</b>" + response['msg']
					}
				} ).catch( function( error ) {
					console.log( error );
				} );
			}
		}
		function createUni() {
			try {
				$('#create_uni_btn').prop('disabled', true);
				let data = {"uni_name": $( '#create-page-modal-toggle .uni_name' ).val()}
				window.axios.post( '{{ route('institutions.create1') }}/'  , data )
				.then( function( response ) {
					console.log(response.data);
					if(response.data.success){
						$('#create-page-modal-toggle').foundation('close');
						window.location.href = response.data.data.redirect_url;
					}

					$('#create_uni_btn').prop('disabled', false);
					
				} ).catch( function( error ) { 
					$('#create_uni_btn').prop('disabled', false);
				} );
			} catch (error) {
				console.log(error);
				$('#create_uni_btn').prop('disabled', false);
			}
		}
		function populateSearchTags(tag) {
			
			window.axios.get('{{route('search.user.history')}}')
			.then(function (response) {
				if (response.data && response.data.success) {
					var defaulter = response.data.data.length==0 ? true: false;
					this.getDefaultTrends(tag, defaulter);
					if(!defaulter){
						tag.defaultTags = response.data.data;
						var item = response.data.data.length > 3 ? response.data.data.slice(0,3) : response.data.data;
						$.each(item, function (indexInArray, valueOfElement) { 
							$('#tags-sidebar-panel-toggle1').append("<li onclick=\"location.href='/search/user/all?q="+valueOfElement.hashtag+"';\"><strong>"+valueOfElement.hashtag+"</strong></li>")
						});
					}
				}
			  })
			.catch(function (error) {
				console.log(error);
			  })

			
		}
		function getDefaultTrends(tag, setDefault = true) {
			window.axios.get('{{route('api.tags.trending.list')}}')
			.then(function (response) {
				if (response.data && response.data.sucess) {
					if(setDefault){
						tag.defaultTags = response.data.data;
						$('.tags-sidebar-recent-history').addClass('hide')
					}
					if($('#tags-sidebar-panel-toggle3')){
						$.each(response.data.data, function (indexInArray, valueOfElement) { 
							$('#tags-sidebar-panel-toggle3').append("<li onclick=\"location.href='/search/user/all?q="+valueOfElement.hashtag+"';\"><strong>#</strong> "+valueOfElement.hashtag.replace('#','')+"</li>");
						});
					}
					tag.update();
				}
			  })
			.catch(function (error) {
				console.log(error);
			  })
		}
		$(document).ready(function () {
			window.riot.mount('header-search', {
				searchActionurl: "{{route('api.search.header')}}",
				placeHolderText: "{{trans('lang.home-top-search-default')}}",
				defaultTrendLoad: populateSearchTags
			}); 
		});
		
		$.notify.addStyle('bigfont', {
			html: "<div><span data-notify-text/></div>",
			classes: {
				base: {
					// "background-color": "lightblue",
					"padding": "5px",
					"background": "green",
					'bottom': '63px',
					"font-size": "22px",
					"border-radius": "10px",
					"color": "white",
				}
			}
		});
		$.notify.defaults({ className: "success",style: "bigfont", autoHide: false, clickToHide: true, });
		

	</script>
</body>
</html>