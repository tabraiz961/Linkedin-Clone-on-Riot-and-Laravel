<raw>
	<span></span>
	
	this.root.innerHTML = opts.content
</raw>


<like-button>
	
	<div ref="dropdown" class="dropdown-pane like-options top" id={ compId } data-dropdown="" data-hover="true" data-hover-pane="true" data-position="top" data-alignment="center">
		<button ref="button" type="button" each="{ content, name  in reactions }" class="button { selected == name ? 'selected' : '' }" onclick={ reactionClicked }>
			<raw content={ content }></raw>
		</button>
	</div>

	<button if={opts.iscomment} ref="button" class="button" data-toggle={ compId } type="button"  data-dropdown="" data-hover="true" data-hover-pane="true" data-position="top" data-alignment="center">
		<span class="reaction-count">{ ( opts.item.reactions != null && opts.item.reactions.length > 0 ? opts.item.reactions.length + ' Likes' : 'Like' )} </span>
	</button> 
	<!-- Comment Like -->
	<button if={!opts.iscomment} ref="button" class="button comment_like" data-toggle={ compId } type="button" onclick={ toggleDropdown }>
		<span class="reaction-count">Like</span>
	</button>
	
	<div ref="dropdown_show" class="dropdown-pane show"  >
		<div each="{ reaction in opts.item.reactions }" class="reaction-list">
			<span class="reaction-icon">
				<raw content={ reactions[ reaction.type ] }></raw> 
				{ reaction.name } { reaction.surname }
			</span>
		</div>
	</div>


	<style>
		button {
			margin: 0 !important;
		}

		.dropdown-pane button {
			font-size: 24px;
			padding: 4px 8px;
		}

		/* .dropdown-pane button:not(.selected):not(:hover) {
			background-color: none	;
		} */

		.dropdown-pane {
			padding: 0;
			width: auto;
		}

		.dropdown-pane.add {
			left: 1.8rem;
			bottom: 2.5rem;
			transform: translateX(-80%);
			z-index: 11;
		}

		.reaction-list {
			margin: 0.5rem 0.5rem 0;
		}

		.reaction-list:last-of-type {
			margin-bottom: 0.5rem; 
		}
		.comment_like {
			color: black;
		}
		.dropdown-pane.like-options {
			background: #e5dfdf;
    		border-radius: 5px;
			border: none; 
		}
		
		.dropdown-pane.like-options.is-open button {
			/* transform: translateY(-11px); */
    		transition: 0.25s cubic-bezier(0.25, 0, 0, 1);
		}
		
		.dropdown-pane.like-options.is-open button:nth-child(1)  {
			transition-delay: 0.0s;
		}
		.dropdown-pane.like-options.is-open button:nth-child(2)  {
			transition-delay: 0.05s;
		}
		.dropdown-pane.like-options.is-open button:nth-child(3)  {
			transition-delay: 0.1s;
		}
		.dropdown-pane.like-options.is-open button:nth-child(4)  {
			transition-delay: 0.15s;
		}
		.dropdown-pane.like-options.is-open button:nth-child(5)  {
			transition-delay: 0.2s;
		}

		.dropdown-pane.like-options button:hover {font-size: 30px; background:none}
		span.reaction-count {color: black;}
	</style>

	<script>
		this.compId = Date.now();
		this.reactions = {
			"LIKE": "&#x1F44D;",
			"LOVE": "&#x2764;",
			"LOL": "&#x1F606;",
			"WOW": "&#x1F62E;",
			"ANGRY": "&#x1F621;"
		};
		this.selected = null;
		let that = this;

		buildPath() {
			let path = opts.addsopts.baseapipath;
			if( opts.iscomment === "true" || opts.iscomment === true ) {
				path +=  '/comment/' + opts.item.comment_id + '/reaction';
			} else {
				path +=  '/post/' + opts.item.post_id + '/reaction';
			}
			return path;
		}

		toggleDropdown( e ) {
			e.stopImmediatePropagation();
			e.preventDefault();
			console.log(that.refs);
			$( that.refs.dropdown ).toggle();
			$( that.refs.dropdown ).toggleClass( 'is-open' );
		}

		reactionClicked( e ) {
			$( that.refs.dropdown ).hide();
			$( that.refs.dropdown ).removeClass( 'is-open' );
			if( e.item.name == that.selected ) {
				let data = {
					'_method': 'DELETE',
				};
				if( opts.iscomment === "true" || opts.iscomment === true ) {
					data.comment_id = opts.item.comment_id;
				} else {
					data.post_id = opts.item.post_id;
				}
				window.axios.post( that.buildPath(), data )
					.then( function( response ) {
						that.reloadReactions();
					}).catch( function( error ) {
					console.log( error );
				});
			} else {
				let data = {
					'_method': 'PUT',
					type: e.item.name
				};
				if( opts.iscomment === "true" || opts.iscomment === true ) {
					data.comment_id = opts.item.comment_id;
				} else {
					data.post_id = opts.item.post_id;
				}
				window.axios.post( that.buildPath(), data )
					.then( function( response ) {
						that.reloadReactions();
						$(that.refs.button).foundation();
					}).catch( function( error ) {
					console.log( error );
				});
			}
		}

		reloadReactions() {
			window.axios.get( that.buildPath() )
				.then( function( response ) {
					opts.item.reactions = response.data;
					that.checkSelected();
					that.update();
				}).catch( function( error ) {
					console.log( error );
			});
		}

		checkSelected() {
			that.selected = null;
			if( opts.item.reactions != null ) {
				for( let i = 0; i < opts.item.reactions.length; i++ ) {
					if( opts.item.reactions[ i ].author_id == opts.addsopts.currentuserid ) {
						this.selected = opts.item.reactions[ i ].type;
					}
				}
			}
		}

		this.on( 'mount', function() {
			$( that.refs.button ).foundation();
			$( that.refs.dropdown_show ).foundation();
			$( that.refs.dropdown ).foundation();
			that.checkSelected();
		});
	</script>
</like-button>
