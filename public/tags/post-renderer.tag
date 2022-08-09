<author-tag>
	<a href={ opts.addsopts.basepath + '/' + opts.item.username }><img if={ !opts.picture } src={ __getProfileUrl( opts.item.photo_url ) } /> { opts.item.name } { opts.item.surname }</a>
</author-tag>

<post-renderer>
	<div class="post form-container" if={ opts.item.type == 'POST' }>
		<div class="author flex-container">
			<!-- <author-tag item={ opts.item } addsopts={ opts.addsopts } ></author-tag> -->
			<a class="flex-container" href={ opts.addsopts.basepath + '/profile/' + opts.item.username }>
				<img src={ opts.item.photo_id } > 
				<div class="home_profile_details">
					<p >{ opts.item.curated_name }</p>
					<p class="time" >{  opts.item.created_at_curated  }</p>
				</div>
			</a>
			
			<div  class="dropdown-pane tiny post_options_container" data-position="bottom" data-alignment="right" id="show_post_options{opts.item.post_id}" data-dropdown data-auto-focus="true" ref="show_post_options_child">
				<div  class="post_actions" if={opts.item.canEditPost}>
					<span class="visibility-label" data-open="postEditModal" onclick={editClick} >
						Edit
					</span>
					<span class="visibility-label">
						Delete
					</span>
				</div>
			</div>
			<div if={ !opts.addsopts.searchType } class="menu__wrapper" data-toggle="show_post_options{opts.item.post_id}" ref="show_post_options">
				<div class="menu__item--meatball">
					<div class="circle"></div>
					<div class="circle"></div>
					<div class="circle"></div>
				</div>		 
			</div>
		</div>
		<div class="content" >
			<p if={ opts.item.description != null }>
				<rawer if={ opts.addsopts.searchType }></rawer>
				<virtual if={ !opts.addsopts.searchType }>{opts.item.description}</virtual>
			</p>
		</div>
		<loader if={ loading }></loader>
		<!-- <div class="subposts" if={ ( subposts != null ) && ( subposts.length > 0 ) }>
			<virtual each={ subpost in subposts } if={ !hasSubPostError }>
				<post-renderer item={ subpost } addsopts={ parent.opts.addsopts }></post-renderer>
			</virtual>
		</div> -->
		<div class="subposts" if={ opts.item.type == 'POST' && opts.item.post_type }>
			<a class="thumbnail" href={opts.item.image_url} download={opts.item.image_url} style="display: block;" if={ opts.item.type == 'POST' && opts.item.post_type =="FILE" }>
				<div class="downloadFile flex-container">
					<div>{opts.item.curated_file_name}</div>
					<div class="timeline-file-icon" >
						<img src="/images/timeline_file_default.png" class="">
					</div>
				</div>
			</a>
			<a class="thumbnail" if={ opts.item.type == 'POST' && opts.item.post_type =="IMAGE" }>
				<img src={ opts.item.image_url } />
			</a>
			
			<a class="thumbnail" if={ opts.item.type == 'POST' && opts.item.post_type =="VIDEO" }>
				<video
					id="my-video"
					class="video-js"
					controls
					width="568px"
					height="291px"
					preload="auto"
					data-setup="{}"
					fluid="true"
				>
					<source src="{opts.item.image_url}#t=1" type="video/mp4" />
				</video>
			</a>
		</div>
		<div class="callout alert" if={ hasSubPostError }>
			<p><i class="fas fa-exclamation-triangle"></i> Erreur lors du chargements des éléments de la publication.</p>
		</div>
		<div class="toolbar">
			<like-button item={ opts.item } addsopts={ opts.addsopts } iscomment="false"></like-button>
			<button class="button" onclick={ toggleComment }>Comment</button>
		</div>
		<comment-section if={ commentOpened } post_id={ opts.item.post_id } addsopts={ opts.addsopts }></comment-section>
	</div>

	<div class="post form-container" if={ opts.item.type == 'SHARE' && subposts != null }>
		<div class="author">
			<author-tag item={ opts.item } addsopts={ opts.addsopts }></author-tag> a partagé le poste de <author-tag item={ subposts[ 0 ] } addsopts={ opts.addsopts } picture="false"></author-tag> · <a class="time" href={ opts.addsopts.basepath + '/post/' + opts.item.post_id }>{ moment( opts.item.created_at ).fromNow() }</a>
		</div>
		<div class="content" >
			<p if={ opts.item.description != null }>{ opts.item.description }</p>
		</div>
		<div class="subposts">
			<virtual each={ subpost in subposts } if={ !hasSubPostError }>
				<post-renderer item={ subpost } addsopts={ parent.opts.addsopts }></post-renderer>
			</virtual>
		</div>
		<div class="callout alert" if={ hasSubPostError }>
			<p><i class="fas fa-exclamation-triangle"></i> Erreur lors du chargements des éléments de la publication.</p>
		</div>
		<div class="toolbar">
			<like-button item={ opts.item } addsopts={ opts.addsopts } iscomment="false"></like-button>
			<button class="button float-right"><i class="fas fa-comments"></i></button>
			<button class="button float-right"><i class="fas fa-share"></i></button>
		</div>
	</div>

	<a class="thumbnail" if={ opts.item.type == 'IMAGE' } onclick={ imageOpen }>
		<img src={ opts.item.image_url } />
	</a>

	<a class="thumbnail" if={ opts.item.type == 'VIDEO' } onclick={ videoOpen }>
		<video src={ opts.item.video_url }>
			<div class="callout alert">
				<p><i class="fas fa-exclamation-triangle"></i> Vid"o non  prise en charge par le navigateur.</p>
			</div>
		</video>
	</a>
	<div ref="postEditModal"  class="tiny reveal" id="postEditModal" data-reveal data-close-on-click="true">
		<div class="subposts" if={ itemEditing && itemEditing.type == 'POST' && itemEditing.post_type }>
			<div if={itemEditing.post_type =="FILE" }>
				<div class="content"> <p>{itemEditing.description}</p> </div>
				<a class="thumbnail" href={itemEditing.image_url} download={itemEditing.image_url} style="display: block;" >
					<div class="downloadFile flex-container">
						<div>{itemEditing.curated_file_name}</div>
						<div class="timeline-file-icon" >
							<img src="images/timeline_file_default.png" class="">
						</div>
					</div>
				</a>
			</div>
			<a class="thumbnail" if={  itemEditing.post_type =="IMAGE" }>
				<img src={ itemEditing.image_url } />
			</a>
			
			<a class="thumbnail" if={  itemEditing.post_type =="VIDEO" }>
				<video
					id="my-video"
					class="video-js"
					controls
					width="568px"
					height="291px"
					preload="auto"
					data-setup="{}"
					fluid="true"
				>
					<source src="{itemEditing.image_url}#t=1" type="video/mp4" />
				</video>
			</a>
		</div>
		
		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<style>
		.loader,
		.post .content,
		.post .toolbar,
		.post .subposts {
			/* border-top: 1px solid hsla(0,0%,4%,.25); */
		}
		.post .subposts {
			text-align: center;
		}
		.loader,
		.post .author{
			padding: 0.5rem 1rem;
		}

		a.thumbnail {
			display: inline-block;
			cursor:auto;
			width: -webkit-fill-available;
		}
		a.thumbnail img{
			width: -webkit-fill-available;
		}
		.post.form-container a.thumbnail .video-js {
			width: -webkit-fill-available;
			height: -webkit-fill-available;
		}
		/* .thumbnail img, */
		/* .thumbnail video {
			width: 128px;
			height: 128px;
		} */

		.post .author img {
			width: 3rem;
			height: 3rem;
			border: 1px solid hsla(0,0%,4%,.25);
			border-radius: 50%;
			margin-right: 0.5rem;
		}
		.post .author.flex-container{
			justify-content: space-between;
		}
		.post .author a {
			color: inherit;
		}

		.post .author a:hover {
			color: #1468a0;
		}

		.post .author .time {
			color: #999;
			margin-bottom: 0px;
			font-size: 11px;
			margin-top: -2px;
		}

		.post .content {
			padding: 0.5rem 1rem;
		}
		.post .content p{
    		white-space: pre-line;
		}

		.post .content p:last-of-type {
			margin-bottom: 0;
		}

		.post .subposts:empty {
			border-top: none;
			padding: 0;
		}

		.post .toolbar {
			position: relative;
			/* border-bottom: 1px solid #c2c2c270; */
		}
		.post .fas.fa-thumbs-up, .post .fas.fa-comment-alt {
			font-size: 22px;
		}
		.post .toolbar > * {
			margin-bottom: 0;
		}

		.post .toolbar .reaction-count {
			margin-left: 0.4em;
		}

		.post .callout.alert {
			border-left: none;
			border-right: none;
			border-bottom: none;
		} 
		.author .home_profile_details{
			display: flex;
			flex-direction: column;
		}
		.author .home_profile_details p:first-child {
			margin: unset;
			font-weight: 700;
		}
		/* .post .author .menu__wrapper {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			flex: 1;
		} */
		.post .author .menu__wrapper > div {
			width: 31px;
			margin-top: 15px;
			padding: 0px;
			display: flex;
			align-items: center;
			cursor: pointer;
		}
		.post .author .menu__wrapper .menu__item--meatball .circle {
			width: 6px;
			height: 6px;
			margin: 2px;
			background: black;
			border-radius: 50%;
			display: block;
		}
		.post .toolbar button {
			background-color: transparent;
			color: black;
			padding: 4px 8px;
		}
		.post button .fas.fa-thumbs-up,.post button .fas.fa-comment-alt {
			color: black;
		}
		.post_actions {
			display: flex;
			flex-direction: column;
		}
		.post_options_container {
			padding: 0.5rem;
		}
		.post_actions span.visibility-label:hover {
			background: #818382 !important;
			cursor: pointer;
		}
		.downloadFile {
			height: 66px;
			background: #e7e7e7;
			align-items: center;
			padding: 0px 16px;
			justify-content: space-between;
		}
		.downloadFile:hover {
			cursor: pointer;
		}
		.timeline-file-icon {
			outline: 2px solid #CCC;
			outline-offset: 7px;
			outline-style: auto;
		}
		.timeline-file-icon img {
			height: 30px;
		}
		.timeline-file-icon:hover {
			outline: 2px solid #b9b6b6;
		}
	</style>

<script>
	this.subposts = [];
	this.loading = false;
	this.hasSubPostError = false;
	this.commentOpened = false;
	this.itemEditing = null;
	let that = this;
	editClick (e){
		console.log(e);
		if(e.item.item){
			// $( that.refs.postEditModal ).foundation( 'open' );
			// var elem2 = new Foundation.Reveal($('#postEditModal'));
			// elem2.open();
			that.itemEditing = e.item.item;
		}
	}
	toggleComment( e ) {
		e.preventDefault();
		e.stopImmediatePropagation();
		that.commentOpened ^= 1;
		that.update();
	}

	imageOpen( e ) {
		console.log(e);
		$( 'body' ).append( $( '<image-popup></image-popup>' ) );
		window.riot.mount( 'image-popup', { item: e.item.subpost } );
	}

	videoOpen( e ) {
		$( 'body' ).append( $( '<video-popup></video-popup>' ) );
		window.riot.mount( 'video-popup', { item: e.item.subpost } );
	}

	setSubPosts( subposts ) {
		that.subposts = subposts;
		that.update();
	}

	subPostError() {
		that.hasSubPostError = true;
		that.update();
	}

	loadSubPosts() {
		if( opts.item.subposts !== undefined && opts.item.subposts.length > 0 ) {
			that.loading = true;
			that.update();
			window.axios.post( opts.addsopts.baseapipath + '/post/gets', { ids: opts.item.subposts }  )
			.then( function( response ) {
					__post__loadAdditional( opts.addsopts.baseapipath, response.data, function( items ) {
						that.loading = false;
						that.setSubPosts( items );
						// setTimeout(function () {
							// $(that.refs.show_post_options_child).foundation();
						// },2000)
					}, function( error ) {
						that.loading = false;
						that.subPostError();
						console.log( error );
					});
					
				
				}).catch( function( error ) {
					that.loading = false;
					that.subPostError();
					console.log( error );
				}).then(function (item) {
				});
		}
	}
	this.on( 'mount', function() {
			
			that.loadSubPosts();
			riot.tag('rawer', opts.item.description);
			riot.mount('rawer');
			that.update();

			$(this.refs.show_post_options_child).foundation();
			$(that.refs.postEditModal).foundation();
			// $(that.refs.removeInstituteModal).foundation()
			
		});

	</script>
</post-renderer>