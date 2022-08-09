


<job-listing-item if={ opts.job }>
	<div class="flex-container job-listingitem-withbutton">
		<div id={opts.indexer +'_jobpost' } class="cell medium-12 large-12 callout border-rad-10 clickable" style="border-color: {opts.clicked && opts.job.job_id &&  opts.job.job_id == opts.clicked.job_id ? '#385072':'#c2c2c2'}"  onclick={hit}>
			<div class="job-details-actions">
				<div class="job-details">
					<div class="heading" >
						<b>{opts.job.position_curated}</b>
					</div>
					<div class="institute">
						{opts.job.institution_name_curated}
					</div>
					<div class="job_loc_type">
						<span>{opts.job.job_location_curated} </span>
						<p>·</p>
						<span>{opts.job.job_type_curated}</span> 
					</div>
					<div  class="job_loc_type">
						<span if={ opts.job.min != 0 && opts.job.max != 0 }>
							{ "USD "+ opts.job.min + ' - ' + 'USD ' + ' ' + opts.job.max } 
						</span>
						<span if={ opts.job.fixed &&  opts.job.fixed != 0 }>
							{ "USD "+ opts.job.fixed  } 
						</span>
							<p>·</p> 
						<span>{ opts.job.salary_type == "month" ? "MONTHLY":"HOURLY" }</span>
					</div>
					<div if={opts.job.gender_prefer != 'any'} class="gender_prefer">
						<span>{opts.job.gender_prefer_curated}</span>
					</div>
					<div class="job_description">
						<span if={opts.job.description.split(/\r\n|\r|\n/).length > 4}>{opts.job.description }</span>
							<virtual if={opts.job.description.split(/\r\n|\r|\n/).length > 4} >
								<br/><a>..show more</a> 
							</virtual>
						<span if={opts.job.description.split(/\r\n|\r|\n/).length < 5}>{opts.job.description}</span>
					</div>
					<div class="curated_created_time">
						<div><span>{ opts.job.created_at_curated }</span></div>
						
					</div>
				</div>
				<div class="job-card-actions">
					<!-- data-toggle="show_post_options{opts.item.post_id}" -->
					<div class="menu__wrapper" if={ false }  ref="show_post_options">
						<div class="menu__item--meatball">
							<div class="circle" ></div>
							<div class="circle"></div>
							<div class="circle"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="flex-vertical-direction" >
			<button class="button copyjob_icon" id="copyBtnToolTip{opts.indexer}" onclick={  } type="button" data-tooltip tabindex="1" title="Copy Job Link" data-position="left" data-alignment="center"><i class="fas fa-share"></i></button>
			<virtual if={ opts.job.is_owner_requested } >
				<button class="border-rad-10 button edit-job-button"  onclick={ editMe } ><i class="fas fa-edit" ></i></button>
				<button class="border-rad-10 button edit-job-button"  onclick={ askDelConfirm } ><i class="fas fa-trash" ></i></button>
			</virtual>
		</div>
	</div>

	
	<div if={ opts.job.is_owner_requested } class="tiny reveal" id="deleteJob" class="border-rad-10" data-reveal data-close-on-click="true">
		<div  class="cv_required_modal">
			<p style="color:red;">Are you sure you want to delete this job?</p>
			<div class="space-around">
				 <button class="button border-rad-10" onclick={confirmDelete}>Yes</button>
				 <button class="button border-rad-10">No</button>
			</div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<style>
	.job-details-actions .job-details .job_description span {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 4;
		-webkit-box-orient: vertical;
	}
	.flex-container.job-listingitem-withbutton {
		align-items: center;
		flex-direction: row-reverse;
	}

	button.edit-job-button {
		height: 45px;
		padding: 9px;
	}


	.copyjob_icon {
		padding: 6px;
		border: 1px solid gray;
		text-align: center;
		border-radius: 50%;
		height: 40px;
		width: 40px;
		cursor: pointer;
	}

	.copyjob_icon:hover {
		color: white;
		background: #3388EE;
	}

	.copyjob_icon i {
		color: #ffffff;
	}

	.copyjob_icon:hover i {
		color: white;
	}
	.flex-vertical-direction {
		margin-right: 20px;
	}
	</style>

	<script>
	// 	min-LENGTH
	// hourly salary layout
		this.job = null;
		this.hasError = false;
		this.deleteSelectedItem = null
		let that = this;
		editMe(e){
			window.location = opts.editlink+'/'+e.item.item.job_id;
		}
		confirmDelete(e){
			window.axios.delete(opts.deletelink+"/"+that.deleteSelectedItem.job_id)
    			.then((res) => {
					if(res.data.success){
						$( '#deleteJob' ).foundation('close');
						opts.removeme()
					}else{
						$( '#deleteJob' ).foundation('close');
					}
				});
		}
		askDelConfirm(e){
			$( '#deleteJob' ).foundation('open');
			that.deleteSelectedItem = e.item.item;
			opts.delasked(e.item.item);
			that.update()
		}
		hit (e){
			if(opts.job){
				that.parent.jobToggle(opts.job);
			}
		}
		function mount() {
			that.loadInitial();
			// $( that.refs.visibility_dropdown ).foundation();
			that.update();
			// console.log(opts);
		}
		loadInitial() {
			that.hasError = false;
			that.job = [];
			that.update();
		}
		this.on( 'mount', function() {
			mount();
			$( '#copyBtnToolTip'+opts.indexer ).foundation();
			// console.log(opts);
		} );

		this.on('update', function () {
			$( '#deleteJob' ).foundation();
		})

	</script>
</job-listing-item>