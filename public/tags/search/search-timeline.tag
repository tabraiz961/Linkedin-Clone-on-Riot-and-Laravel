<search-timeline>
	<search-sexy-loader if={loading}>

	</search-sexy-loader>
	<div if={!loading && people.length} class="callout card search-panel border-rad-10">
		<div  class="flex-container flex-dir-column p-10">
			<div class="p-10" >
				<h3 class="fw-6">People</h3>
			</div>
			<div>
				<ul class="no-ul-styles">
					<li each={person in people}>
						<div class="search-person-container flex-container">
							<div class="serach-person-pic "><img class="profile-pic-default" src={ person.details.photo_id } alt=""></div>
							<div class="search-person-detail">
								<div class="search-person-detail-info flex-container flex-dir-column">
									<div class="search-person-info-title fw-6"><a href="/profile/{person.username}">{ person.details.curated_name }</a></div>
									<div class="search-person-info-details flex-container space-between">
										<div class="search-person-info-details-all flex-container flex-dir-column">
											<div>{ person.details.title_curated }</div>
											<div>{ person.mutuals } Mutual Connections</div>
										</div>
										<div class="search-person-info-actions">
											<button  onclick={ decideAction } class="button border-rad-10 fw-6">{ person.isFriend ? 'View' : (person.isRequested ? 'Request Sent' : 'Connect') }</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div if={!loading && institutions.length} class="callout card search-panel border-rad-10">
		<div  class="flex-container flex-dir-column p-10">
			<div class="p-10" >
				<h3 class="fw-6">Institutions</h3>
			</div>
			<div>
				<ul class="no-ul-styles">
					<li each={institute in institutions}>
						<div class="search-person-container flex-container">
							<div class="serach-person-pic "><img class="profile-pic-default institute" src={ institute.institution_photo } alt=""></div>
							<div class="search-person-detail">
								<div class="search-person-detail-info flex-container flex-dir-column">
									<div class="search-person-info-title fw-6"><a href="/institution/{institute.slug}">{ institute.institution_name }</a></div>
									<div class="search-person-info-details flex-container space-between">
										<div class="search-person-info-details-all flex-container flex-dir-column">
											<div>{ institute.public_bio }</div>
											<div>{ institute.typee.type_name } </div>
										</div>
										<div class="search-person-info-actions">
											<button  onclick={ToggleFollow} class="button border-rad-10 fw-6">{ institute.is_following ? 'UnFollow' : 'Follow' }</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<style>
		.search-panel {
			/* border: none !important; */
		}
		.search-person-container .serach-person-pic {
			flex-basis: 10%;
		}
		.search-person-container .search-person-detail {
			flex-basis: 90%;
		}
		img.profile-pic-default {
			height: 60px;
			width: 60px;
		}
		
		
		img.profile-pic-default.institute {
			border-radius: 10px;
		}
		.serach-person-pic {
			display: flex;
			align-items: center;
		}
		.search-person-info-actions button.button {
			min-width: 87.2px;
		}
	</style>
	<script>
		this.data = [];
		this.institutions = [];
		this.people = [];
		this.loading = true;
		let that  = this;
		ToggleFollow(institute){
			window.axios.get(opts.toggle_institute_ask+'/'+institute.item.institute.institution_id).then( function( response ) {
				if(response.data && response.data.success){
					let index=  that.institutions.findIndex(x=> x.institution_id == institute.item.institute.institution_id);
					that.institutions[index].is_following =  that.institutions[index].is_following ? 0 : 1;
					if (that.institutions[index].is_following) {
						$.notify("Now Following", {position:'bottom-right', className: 'base'});
					} else {
						$.notify("Successfully Unfollowed", {position:'bottom-right', className: 'base'});
					}
					that.update(); 
				}				
			} ).catch( function( error ) {
				console.log( error );
			});
		}
		decideAction(person){
			if(person.isFriend){
				window.location.href = window.origin+'/profile/'+ person.username; 
			}else{
				window.axios.get(opts.toggle_people_ask+"/"+person.item.person.user_id).then( function( response ) {
					if(response.data && response.data.success) {
						let index=  that.people.findIndex(x=> x.user_id == person.item.person.user_id );
						that.people[index].isRequested = that.people[index].isRequested ? 0 : 1;
						that.update();
					}
				} ).catch( function( error ) {
					console.log( error );
				});
			}
		}
		init(){
			var obj = { input: opts.searchKeyword, saveSearch: opts.saveSearch };
			window.axios.post(opts.searchUrlWidKeyword, obj)
			.then(function (response) { 
				if (response.data && response.data.sucess) {
					that.data = response.data.data;
					that.people = response.data.data.filter(item => item.type == 'user');	
					that.institutions = response.data.data.filter(item => item.type == 'institutions');	
					
					// console.log(that.people);
					that.loading = false;
					that.update();
				}
			})
			.catch(function (error) { console.log(error);  })
		}
		that.on('mount',function () {
			that.init();
		});
		window.riot.mount('search-sexy-loader', {});
	</script>
</search-timeline>