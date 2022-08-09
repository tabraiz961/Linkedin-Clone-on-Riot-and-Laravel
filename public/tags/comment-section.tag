<comment-section>
	<div class="comments">
		<div class="comment flex-container" each={ comment in comments }>
			<a href={ opts.addsopts.basepath + '/' + comment.username }>
				<div class="comment_img_container">
					<img src={ comment.photo_id } alt="">
				</div>
			</a>
			<div class="comment-detail">
				<div class="comment_user">
					<a class="comment-author" href={ opts.addsopts.basepath + '/' + comment.username }>
						{ comment.curated_name }
					</a>
					<div class="comment-content">
						<p>{ comment.text }</p>
					</div>
				</div>
				<like-button item={ comment } addsopts={ opts.addsopts } iscomment="true"></like-button>
			</div>
		</div>
		<loader if={ loading }></loader>
		<div class="callout alert" if={ hasError }>
			<p><i class="fas fa-exclamation-triangle"></i> Erreur lors du chargements des commentaires.</p>
		</div>
	</div>
	<form ref="form" onsubmit={ submit }>
		<div class="grid-x">
			<div class="cell auto">
				<textarea ref="text_content" name="text" class="text-content" required></textarea>
			</div>
			<div class="cell shrink">
				<button class="button" type="submit"><i class="fas fa-paper-plane"></i></button>
			</div>
		</div>
		<div class="progress" ref="progressBar"></div>
	</form>

	<style>
		:scope {
			display: block;
			/* padding: 0.5rem 1rem; */
			/* background: #e6e6e6; */
		}

		.text-content {
			border: 0;
			margin: 0;
		}

		.progress {
			display: none;
			height: 4px;
			width: 0;
			padding: 0;
			margin: 0;
			background: #23a3ba;
			transition: width 0.25s ease-in;
		}

		button {
			height: 100%;
			margin: 0 !important;
		}

		.comment {
			position: relative;
			border-bottom: 1px solid #c2c2c2;
			padding: 5px 8px;
			/* margin-bottom: 1rem; */
		}

		.comment .comment-author {
			/* display: block; */
			/* padding: 0 0 0 0.5rem; */
			font-weight: 600;
			color: black;
		}

		.comment .comment-content {
			/* display: inline-block; */
			width: auto;
			/* padding: 0.25rem 0.5rem; */
			/* background: white; */
			border-radius: 10px;
		}

		.comment .comment-content p {
			margin: 0;
			font-size: 14px;
		}

		.comment like-button {
			display: block;
			/* position: absolute; */

			left: 0.25rem;
			bottom: -1rem;
		}
		like-button .dropdown-pane.like-options.is-open  button{
			background-color: transparent;
			color: black;
		}

		.comment like-button > button {
			border-radius: 30px;
			font-size: 13px;
			padding: 0rem 0.2rem;
			margin-top: -12px !important;
			background: none;
		}
		.comment like-button > button:hover {
			color: #3388EE;
			background-color: transparent;
		}
		.comment .comment_img_container {
			width: 40px !important;
			height: 40px !important;
			min-width: 40px;
		}
		
		.comment .comment_img_container img{
			border: 1px solid hsla(0,0%,4%,.25);
		}
		
		.comment like-button > button > * {
			font-size: inherit;
		}

		.comment like-button .dropdown-pane.add, .comment [data-is="like-button"] .dropdown-pane.add {
			bottom: 22px;
		}

		/* form {
			margin-top: 1.5rem;
		} */

		form .text-content {
			border-top-left-radius: 10px;
			border-bottom-left-radius: 10px;
		}

		form .button {
			border-top-right-radius: 10px;
			border-bottom-right-radius: 10px;
		}
		.comments .comment a div img {
			height: 40px;
    		border-radius: 50%;
			border: 1px solid hsla(0,0%,4%,.25);
		}
		.comment-detail {
			margin-left: 8px;
		}
		.cell.shrink button {
			color: #3388EE;
			background-color: white;
		}
		.cell.shrink button:hover {
			color: #3388EE;
		}
		.comment .comment_user {
			background-color: #adcff8;
			border-radius: 9px;
			padding: 0px 4px;
		}
	</style>

	<script>
		this.post_id = null;
		this.comments = [];
		this.loading = false;
		this.hasError = false;

		let that = this;

		loadComments() {
			that.loading = true;
			that.hasError = false;
			that.update();
			window.axios.get(
				opts.addsopts.baseapipath + '/post/' + opts.post_id + '/comments',
				null,
				{
					onUploadProgress: function( progressEvent ) {
						that.setProgress( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
					}
				}
			).then( function( response ) {
				that.loading = false;
				that.enable();
				that.setProgress( 0 );
				that.setItems( response.data );
			}).catch( function( error ) {
				that.loading = false;
				that.hasError = true;
				that.enable();
				that.setProgress( 0 );
				console.log( error );
			});
		}

		setItems( items ) {
			that.comments = items;
			console.log(that.comments);
			that.update();
		}

		reload() {
			that.loadComments();
		}

		postComment() {
			that.hasError = false;
			that.disable();
			that.update();
			let data = getForm();
			data[ '_method' ] = 'PUT';
			window.axios.post(
				opts.addsopts.baseapipath + '/post/' + opts.post_id + '/comments',
				data,
				{
					onUploadProgress: function( progressEvent ) {
						that.setProgress( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
					}
				}
			).then( function( response ) {
				that.enable();
				that.setProgress( 0 );
				that.reload();
				console.log();
			}).catch( function( error ) {
				that.hasError = true;
				that.enable();
				that.update();
				that.setProgress( 0 );
				console.log( error );
			});
			that.refs.text_content.value = "";
		}

		this.on( 'mount', function() {
			that.reload();
		});

		function getForm() {
			return {
				text: that.refs.text_content.value,
				post_id: opts.post_id
			};
		}

		setProgress( progress ) {
			that.refs.progressBar.style.width = progress + '%';
			if( progress > 0 ) {
				$( that.refs.progressBar ).show();
			} else {
				$( that.refs.progressBar ).hide();
			}
		}

		clear() {
			that.setProgress( 0 );
			that.refs.text_content.value = "";
			that.update();
		}

		disable() {
			let elems = that.refs.form.querySelectorAll( 'button' );
			for( let i = 0; i < elems.length; i++ ) {
				elems[ i ].disabled = true;
			}
			that.refs.text_content.disabled = true;
		}

		enable() {
			let elems = that.refs.form.querySelectorAll( 'button' );
			for( let i = 0; i < elems.length; i++ ) {
				elems[ i ].disabled = false;
			}
			that.refs.text_content.disabled = false;
		}

		submit( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			that.postComment();
		}
	</script>
</comment-section>