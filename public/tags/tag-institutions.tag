<tag-institutions>
	<div ref="scroller">
		<ul class="feed-follows-module__list">
			<virtual each={item in items}>
				<li class="feed-follows-module-recommendation member">
					<a data-control-name="sidebar_follow_actor_picture" href="/institution/{ item.slug }" class="feed-follows-module-recommendation__profile-link--avatar">
						<img  src={ item.institution_photo } loading="lazy" alt={ item.institution_name } ></img>
					</a>
					<div class="user_details_rightbar flex-container align-center-middle">
						<div class="feed-follows-module-recommendation__description">
							<a data-control-name="sidebar_follow_actor" href="/institution/{ item.slug }" class="feed-follows-module-recommendation__profile-link--description ember-view">        
							<p class="feed-follows-module-recommendation__name-container ">
								<span class="feed-follows-module-recommendation__name dottedSingleTxt">
									{ item.institution_name }
								</span>
							</p> 
								<div class="feed-follows-module-recommendation__subtext">
									<p  class="lt-line-clamp lt-line-clamp--multi-line ember-view feed-follows-module-recommendation__member-headline t-12 t-black--light t-normal" style="-webkit-line-clamp: 2">  
										{item.aluminis  ? item.aluminis: item.public_bio}
									</p>

								</div>
							</a>
						</div>
						<div class="follow_container">
							<button onclick={ connect } data-username={ item.slug } class="follow feed-follows-module-recommendation__follow-btn artdeco-button artdeco-button--secondary artdeco-button--2 artdeco-button--muted" aria-label="Follow" aria-pressed="false" type="button">
								<li-icon aria-hidden="true" type="plus-icon" style="font-weight: bolder;" class="artdeco-button__icon" size="small">
									Follow
								</li-icon>
							</button>
						</div>
					</div>
				</li> 
			</virtual>
			<div class="" if={ this.relatedLoading }>
				<card-loader></card-loader>
			</div>
			<!-- <li class="more_layout" > 
				<div class="home_right_people_connect_more flex-container align-center-middle" > <a href="network">View More Peoples</a> </div>
			</li> -->
		</ul>
		<!-- <div class="text-center" if={!items}>
			<spinner></spinner>
		</div> -->
		<div class="callout alert" if={hasError}>
			<p><i class="fas fa-exclamation-triangle"></i> Erreur lors du chargements des données.</p>
		</div>
	</div>

	<style>
		:scope {
			width: 0;
		height: 0;
		margin: 0;
		}

		spinner {
			display: inline-block;
		}
		.feed-follows-module-recommendation__profile-link--avatar {
			flex-shrink: 0;
			margin: 5px;
		}
		.feed-follows-module-recommendation {
			display: flex;
			align-items: center;
			/* margin: 12px 0; */
			position: relative;
			min-height: 100px;
			min-height: 53px;
		}
		.feed-follows-module-recommendation__description {
			display: block;
			line-height: 2rem!important;
			max-height: 6rem;
			margin-bottom: 4px;
			flex-grow: 1;
			/* overflow: hidden; */
			max-width: 166px;
			margin-right: 2px;
		}
		.feed-follows-module-recommendation__description * {
			line-height: inherit!important;
		}

		.artdeco-button--muted.artdeco-button--secondary {
			background - color: var(--color-background-none);
		color: var(--color-text-low-emphasis-shift);
		box-shadow: inset 0 0 0 1px var(--color-border);
}

		.feed-follows-module-recommendation__profile-link--avatar {
			flex - shrink: 0;
		/* align-self: flex-start; */
		margin-right: 12px;
}
		.feed-follows-module-recommendation__name{
			font-weight: 700;
			color: black;
		}
		.feed-follows-module-recommendation__name, .feed-follows-module-recommendation__subtext{
			max-width: 144px;
		}
		.feed-follows-module-recommendation__name-container {
			line - height: 2rem!important;
			max-height: 4rem;
			overflow: hidden;
			display: flex;
			margin-bottom: 0px
}
		.feed-follows-module-recommendation__name-container * {
			line - height: inherit!important;
}
		[class*=EntityPhoto-circle] {
			border - radius: var(--corner-radius-full)!important;
}

		.artdeco-entity-lockup [class*=artdeco-entity-lockup__image--type-circle] img, .artdeco-entity-lockup [class*=artdeco-entity-lockup__image--type-square] img, [class*=artdeco-entity-pile__image--circle], [class*=artdeco-entity-pile__image--square], [class*=EntityPhoto-circle], [class*=EntityPhoto-square] {
			background - color: var(--color-background-container);
		color: var(--color-text);
		border: none;
		box-shadow: none;
}
		.EntityPhoto-circle-3, .EntityPhoto-circle-3-ghost-person {
			width: 48px;
		height: 48px;
		box-sizing: border-box;
		background-clip: content-box;
		border: 2px solid transparent;
		border-radius: 49.9%;
}

		.lt-line-clamp--multi-line {
			display: -webkit-box;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
}
		.lt-line-clamp {
			overflow: hidden;
		position: relative;
}
		.feed-follows-module-recommendation__description * {
			line - height: inherit!important;
}
		.t-12 {
			--artdeco - reset - typography_getFontSize: 1.2rem;
		font-size: var(--artdeco-reset-typography_getFontSize);
		--artdeco-reset-typography_getLineHeight: 1.33333;
		line-height: var(--artdeco-reset-typography_getLineHeight);
}

		p {
			margin - bottom: 0;
		font-size: inherit;
		line-height: 1.6;
		text-rendering: optimizeLegibility;
}

	.feed-follows-module-recommendation__profile-link--avatar img {
		min-width: 43px;
		border-radius: 5px;
		width: 43px !important;
		border: 1px solid hsla(0,0%,4%,.25);
		height: 43px !important;
	}
	ul.feed-follows-module__list{
		margin-left: 0rem;
	}
	.feed-follows-module-recommendation__member-headline{
		text-align: left;
		font-size: 12px;
		line-height: 15px !important;
		margin-bottom: 1px;
		color: #8b8b8b;
	}
	button.follow.feed-follows-module-recommendation__follow-btn {
		/* border: 1px solid; */
		border-radius: 5px;
		padding: 5px 7px;
		background-color: #e9e9e9;
		font-weight: bolder;
		color: #3589ee;
		/* ff7474 */
	}
	button.follow.feed-follows-module-recommendation__follow-btn:hover {
		background-color: #3589ee;
		color: #e9e9e9;
		cursor: pointer;
	}
	.follow_container{
		text-align: left;
	}
	.user_details_rightbar {
		min-width: 75%;
	}
	button li-icon {
		font-size: 14px;
	}
	.feed-follows-module__list li.more_layout {
		list-style: none;
	}
	.feed-follows-module__list li.more_layout:hover {
		cursor: pointer;
	}
	.feed-follows-module__list li.more_layout .flex-container {
		font-weight: 600;
	}
	li.feed-follows-module-recommendation.member:not(:last-child) {
		border-bottom: 1px solid #c2c2c2;
	}
	ul#header_profile_drop li {
		margin: 0px;
	}
	</style>

	<script>
		this.items = [];
		this.relatedLoading = false;
		this.hasError = false;
		this.noMore = false;

		let that = this;

		reload() {
			that.loadInitial();
		}

		loadInitial() {
			that.relatedLoading = true;
			that.hasError = false;
			that.noMore = false;
			that.items = [];
			that.update();
			opts.load( null, that );
			// console.log(that.items );
		}

		addItems(items) {
			for (let index = 0; index < items.length; index++) {
				items[index]['aluminis'] = items[index]['aluminis'] == 'ㅤ' ? '' : items[index]['aluminis'];
			}
			that.relatedLoading = false;
			if (items == null || items.length === 0) {
				that.noMore = true;
			} else {
				that.items = that.items.concat(items);
			}
			// console.log("ABCD")
			// console.log(this.items)
			that.update();
		}

		error() {
			that.relatedLoading = false;
			that.hasError = true;
			that.update();
		}

		
		this.on( 'mount', function() {
			that.loadInitial();
			if( opts.onMounted ) {
				opts.onMounted(that);
			}

			connect(e) {
				let data = {
					'_method': 'PUT'
				};
				window.axios.post(
					opts.addsopts.baseapipath+'/' + opts.addsopts.follow_ask+e.item.item.slug,
					data
				).then( function( response ) {
					that.items = [];
					opts.load( null, that );
					console.log(response);
				} ).catch( function( error ) {
					console.log( error );
				});
			}
		});

	</script>
</tag-institutions>