<tag-profilepeople>
	<div class="profile-network-container">

	</div>
	<virtual each={item in items}>
		<div class="card">
			<div class="banner" style="background-image:url({item.cover_path})">
				<img src={ item.photo_id } />
			</div>
			<div class="menu"></div>
			<a href="{ item.username }"><h2 class="name">{ item.curated_name }</h2></a>
			<div class="title">
				{item.title_curated }
			</div>
			<div class="actions">
			<!-- <div class="follow-info">
				<p>BS Psychology</p>
				<p>MS Science</p>
			</div> -->
			<div class="follow-btn">
				<button class="button" onclick={ connect } data-username={ item.username } >Connect</button>
			</div>
			</div>
		</div>
	</virtual>

	
	
<style>
	.profile-network-container {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
	}
	.card {
		background-color: #fff;
		max-width: 213px;
		border: 1px solid #c2c2c2;
		display: flex;
		flex-direction: column;
		overflow: hidden;
		border-radius: 15px;
		box-shadow: 0px 1rem 0.5rem rgb(207 207 207 / 50%);
		margin-bottom: 13px;
	}
	.card .banner {
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
		height: 5rem;
		display: flex;
		align-items: flex-end;
		justify-content: center;
		box-sizing: border-box;
	}
	.card .banner img {
		background-color: #fff;
		width: 6rem;
		height: 6rem;
		box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 30%);
		border-radius: 50%;
		transform: translateY(50%);
		/* transition: transform 200ms cubic-bezier(0.18, 0.89, 0.32, 1.28); */
	}
	.card .menu {
		width: 100%;
		height: 3.5rem;
		padding: 1rem;
		display: flex;
		align-items: flex-start;
		justify-content: flex-end;
		position: relative;
		box-sizing: border-box;
		font-size: 20px;
	}
	.card h2.name {
		text-align: center;
		padding: 0 0rem 0.1rem;
		margin: 0;
		font-size: 20px;
		font-weight: 600;
	}
	.card .title {
		color: #a0a0a0;
		font-size: 0.85rem;
		text-align: center;
		padding: 0 0rem 0.2rem;
	}
	.card .actions {
		padding: 5px 5px;
		display: flex;
		flex-direction: column;
		order: 99;
		align-items: center;
	}
	.card .actions .follow-info {
		display: flex;
	}
	.follow-info p {
		color: white;
		background: gray;
		margin: 6px 6px;
		border-radius: 6px;
		padding: 0px 5px;
	}
	.card .actions .follow-btn button {
		border-radius: 5px;
		padding: 8px 18px;
    	margin-bottom: 0px;
		background-color: #3589ee;
		font-weight: bolder;
		color: white;
	}
	.card .actions .follow-btn button:hover {
		background: #2970c5;
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
			that.scrollElementIsDocument = opts.scrollElement === document;
			that.scrollElement = $( opts.scrollElement ? opts.scrollElement : that.refs.scroller );
			that.loadInitial();
			if( opts.onMounted ) {
				opts.onMounted(that);
			}

			connect(e) {
				let data = {
					'_method': 'PUT'
				};
				window.axios.post(
					opts.addsopts.basepath + "/friend/"+e.item.item.username+"/ask",
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
</tag-profilepeople>