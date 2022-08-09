	{{-- @if( !isset( $frame ) || $frame ) --}}
<div id="myprofile-card" class="myprofile-header-card" >
{{-- @endif --}}
	<div class="cover" @if($user['cover_path']) style="background-image: url({{ $user['cover_path'] }});" @endif> </div>
	<div class="myprofile-top-details-container border-bot-rad-15">
		<div class="myprofile-top-details ">
			<div class="myprofile-top-details-items">
				<div class="profile-img">
					<img src="{{ $user['photo_id'] }}"/>
				</div>
			</div>
			<div class="myprofile-actions-container myprofile-top-details-items">
				<div class="myprofile-top-details-items-descriptions">
					<div class="myprofile-top-details-name h4">
						<strong>{{ $user['curated_name'] }}</strong>
					</div>
					<div>
						<p class="m-0 hint-color myprofile-top-details-title" style="margin-bottom: 3px;">{{ $user['title'] }}</p>
						<p class="m-0 hint-color myprofile-top-details-title" style="margin-bottom: 3px;">{{ isset($user['profile_details']['short_bio']) ?$user['profile_details']['short_bio']: 'Update short bio to show here.'  }}</p>
					</div>
					{{-- <div class="myprofile-top-details-degrees">
						<p>BS Psychology</p>
						<p>MS Science</p>
					</div> --}}
				</div>
				<div class="myprofile-top-details-actions">
					<div>
						@if ($user['user_type'] == 'LoggedUser')
							<div type="button"  class="button edit-profile-basic" data-toggle="profileEditModal" aria-controls="profileEditModal" aria-haspopup="true" tabindex="0"><i class="fas fa-pen-square"></i></div>
						@endif
					</div>
					@auth
					<div class="follow_container"> 
						@if (Auth::user()->user_id !== $user['user_id'] )
							<button onclick="profileConnect(this)" data-username="{{$user['username']}}" class="follow" aria-label="Follow" type="button" >
									<li-icon type="plus-icon" style="font-weight: bolder;" class="artdeco-button__icon"  size="small"> Connect </li-icon> 
							</button> 
						@endif 
					</div>
					@endauth
				</div>
			</div>
		</div>
		<div class="myprofile-top-details-desctip">
			@if (isset($user['profile_details']['long_description']))
				<div><p class="m-0 h5"><b>Introduction</b></p></div>
				<div class="myprofile-top-details-des-intro"><p>{{ isset($user['profile_details']['long_description']) ? $user['profile_details']['long_description'] : 'Update you profile description here, who are you ? What\'s your next goal? Anyone inspird from? What makes you special?'  }}</p></div>
			@endif
		</div>
	</div>
</div>

<style>
	div#myprofile-card, .callout.profile-header-card.tags{
		border-radius: 15px;
	}
	.callout.profile-header-card.tags.sticky.is-at-top.is-stuck {
		margin-top: 3rem !important;
	}
	ul.tags-list li {padding-left: 13px;color: #385072;list-style: none;}

	ul.tags-list {margin: 6px 0px;}

	ul.tags-list li:hover {color: white;background: #adcff8;}

	ul.tags-list li strong {font-size: 18px;padding-right: 8px;}
	.top-tags-sidebar .tags-block-sidebar {
		padding: 0px 7px 0px 5px;
	}
	.tags-block-sidebar .link-container {
		padding: 0px 8px;
		cursor: pointer;
	}
	.toggle-link {
		position: relative;
		display: inline-block;
		padding-right: 20px;
		color: #747474;
		font-size: 22px;
		line-height: 1;
		font-family: 'Source Sans Pro', sans-serif;
		text-decoration: none;
		cursor: pointer;
	}
	.toggle-link::before,
	.toggle-link::after {
		display: block;
		content: "";
		position: absolute;
		top: 50%;
		height: 2px;
		width: 8px;
		background-color: #747474;
		transition: all 250ms cubic-bezier(0.645, 0.045, 0.355, 1);
		backface-visibility: hidden;
	}
	.toggle-link.horizontal::before {
		right: 8px;
		transform-origin: 8px 1px;
		transform: translateY(4px) rotate(45deg);
	}
	.toggle-link.horizontal::after {
		right: 0px;
		transform-origin: 0px 1px;
		transform: translateY(4px) rotate(-45deg);
	}
	.toggle-link.horizontal.makeUp::before {
		transform: translateY(-4px) rotate(-45deg);
	}
	.toggle-link.horizontal.makeUp::after {
		transform: translateY(-4px) rotate(45deg);
	}
	.myprofile-header-card .myprofile-top-details-title{
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2; /* number of lines to show */
				line-clamp: 2; 
		-webkit-box-orient: vertical;
	}
	
	.myprofile-header-card .myprofile-top-details-des-intro p{
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 6; /* number of lines to show */
				line-clamp: 6; 
		-webkit-box-orient: vertical;
	}
</style>
	<script>
		var connect = true;
		function profileConnect(e) {
			console.log(e);
			return;
			if(connect){
				window.axios.get('friend/'+e+'/status')
				.then( function( response ) {
					if(response.success){
						
						this.connect = false;
					}
				})
				.catch( function( error ) {
					tag.error();
					console.log( error );
				} );
				this.innerText = "Sent";
				this.disabled = true;
			}
		}
	</script>