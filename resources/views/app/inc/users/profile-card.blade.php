<div data-sticky-container>
	@if( !isset( $frame ) || $frame )
		<div id="profile-card" class="callout profile-header-card" {!! isset( $sticky ) ? 'data-anchor="' . $sticky[ 'element' ] . '" data-margin-top="' . $sticky[ 'top' ] . '"' : '' !!}>
	@endif
		<div class="header" @if($user['cover_path']) style="background-image: url('{{ $user['cover_path'] }}');" @endif>
			<img src="{{ $user['photo_id'] }}"/>
		</div>
		<div class="summary">
			<h5><a href="{{ route( 'user.profile', [ 'username' => $user['username'] ] )}}">{{ $user['curated_name'] }}</a></h5>
			<h6>{{ $user['title'] or 'A short bio here' }}</h6>
		</div>
		<div id="ember357" class="feed-identity-module__entity-list entity-list-wrapper ember-view">
			<ul class="entity-list row">
				<li class=" entity-list-item">
						<a href="/me/profile-views/" >
							<div class="space-between">
								<div >
									Who viewed your profile
								</div>
								<div>
									39
								</div>
							</div>
						</a>
				</li>
				
				<li class=" entity-list-item">
						<a href="/me/profile-views/" >
							<div class="space-between">
								<div >
									Views of your post
								</div>
								<div>
									910
								</div>
							</div>
						</a>
				</li>
		
				<li class=" entity-list-item">
					<a href="/me/profile-views/" >
						<div class="space-between">
							<div >
								Followers
							</div>
							<div>
								5
							</div>
						</div>
					</a>
				</li>
				<li class=" entity-list-item">
					<a href="/me/profile-views/" >
						<div class="space-between">
							<div >
								Following
							</div>
							<div>
								910
							</div>
						</div>
					</a>
				</li>
				<li class=" entity-list-item">
					<a href="/me/profile-views/" >
						<div class="space-between">
							<div >
								Posted Works
							</div>
							<div>
								910
							</div>
						</div>
					</a>
				</li>
				<li class=" entity-list-item">
					<a href="/me/profile-views/" >
						<div class="space-between">
							<div >
								Videos
							</div>
							<div>
								910
							</div>
						</div>
					</a>
				</li>
				
			</ul>
		
			<!---->
		</div>

		{{-- <div class="profileview"><a href="{{ route( 'user.profile', [ 'username' => $user['username'] ] ) }}">View Profile</a></div> --}}
@if( !isset( $frame ) || $frame )
	</div>
@endif



	<div class="callout profile-header-card tags sticky" data-sticky  data-top-anchor="470">
		<div class="top-tags-sidebar" >
			<div class="tags-block-sidebar">
				<div class="flex-container space-between tags-sidebar-recent-history">
					<div >Recently Viewed</div>
					<div data-toggle="tags-sidebar-panel-toggle1 t1" class="link-container"><a id="t1"  data-toggler="makeUp" class="toggle-link horizontal"></a></div>
					<!-- <div ><a data-toggle="tags-sidebar-panel-toggle1">Arrow</a></div> -->
				</div>
				<ul  id="tags-sidebar-panel-toggle1" data-toggler="is-hidden"  class="tags-list">
					{{-- <li><strong>#</strong> TeachersDay</li>
					<li><strong>#</strong> ProblemOfTheDay</li>
					<li><strong>#</strong> TipsForHer</li> --}}
				</ul>
			</div>
			<div class="tags-block-sidebar">
				<div class="flex-container space-between">
					<div>Followed</div>
					<div data-toggle="tags-sidebar-panel-toggle2 t2" class="link-container"><a id="t2"  data-toggler="makeUp" class="toggle-link horizontal"></a></div>
				</div>
				<ul  id="tags-sidebar-panel-toggle2" data-toggler="is-hidden"  class="tags-list">
					<li><strong>#</strong> TeachersDay</li>
					<li><strong>#</strong> ProblemOfTheDay</li>
					<li><strong>#</strong> TipsForHer</li>
				</ul>
			</div>
			<div class="tags-block-sidebar">
				<div class="flex-container space-between">
					<div>Top Trending</div>
					<div data-toggle="tags-sidebar-panel-toggle3 t3" class="link-container"><a id="t3"  data-toggler="makeUp" class="toggle-link horizontal"></a></div>
				</div>
				<ul  id="tags-sidebar-panel-toggle3" data-toggler="is-hidden"  class="tags-list">
				</ul>
			</div>
		</div>
	</div>
</div>

<style>
	div#profile-card, .callout.profile-header-card.tags{
		border-radius: 15px;
	}
	.callout.profile-header-card.tags.sticky.is-at-top.is-stuck {
		margin-top: 4rem !important;
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
</style>