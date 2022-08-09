@foreach( $users as $user )
	{{-- <a href="{{ route( 'user.profile', [ 'username' => $user['username'] ] ) }}" class="friend-line grid-x grid-padding-x">
		<div class="cell shrink">
			<img src="{{ $user['photo_id'] or \App\User::default_photo_url }}" />
		</div>
		<div class="cell auto">
			<p>{{ $user['curated_name']}}</p>
		</div>
	</a> --}}
	<div class="callout card">
		<div class="network-profile-cover">
			<img src="" alt="">
		</div>
		<div class="network-profile-details">
			<div class="network-profile-image">
				<a href="{{ route( 'user.profile', [ 'username' => $user['username'] ] ) }}">
					<img src="{{ $user['photo_id'] or \App\User::default_photo_url }}" alt="">
				</a>
			</div>
			<div class="network-profile-name">
				<a href="{{ route( 'user.profile', [ 'username' => $user['username'] ] ) }}">
					<p>{{ $user['curated_name']}}</p>
				</a>
			</div>
			
			<div class="network-profile-specialization">
				<div class=""><p>BS Psychology</p></div><div><p>MS Science</p></div>
			</div>
			@if (isset($user['friends']))
				<div class="network-profile-mutual-details">
					<p>{{$user['friends']['mutual_friends_count'] }} mututal connections</p>
				</div>
			@else
				<div class="network-profile-mutual-details">
					{{-- <p>{{ $address }}</p> --}}
				</div>
			@endif
			
			<div class="network-profile-connect-btn">
				<button class="button" onclick="connectNetwork({{$user['user_id']}})">Connect</button>
			</div>
		</div>
	</div>
@endforeach

{{ method_exists( $users, 'links' ) ? $users->links() : '' }}